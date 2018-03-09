<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CurrencyConverterController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $currencies = new \App\Currency();

        $array = $currencies->getArray();

        return view('currencyConverter', compact('array'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $this->validate($request, [
            'amount' => 'required|numeric|max:1000000',
            'first_currency' => 'required',
            'second_currency' => 'required'
        ]);

        $currencyConverter = [];

        $currencyConverter['amount'] = $request->get('amount');
        $currencyConverter['first_currency'] = $request->get('first_currency');
        $currencyConverter['second_currency'] = $request->get('second_currency');

        $url = 'https://api.abucoins.com/products/ticker';

        $currencies = json_decode(file_get_contents($url), true);

        $value = $currencyConverter['first_currency'] . '-' . $currencyConverter['second_currency'];

        //obsługa zamiany walut gdzie łańcuch $value jest zgodny z wartością z API(bezpośrednio)
        foreach (array_values($currencies) as $currency) {
            if ($currency['product_id'] == $value) {
                return redirect()->back()->with('message', $currencyConverter['amount'] . ' ' . $currencyConverter['first_currency'] . ' = ' .
                                $currencyConverter['amount'] * $currency['price'] . ' ' . $currencyConverter['second_currency']);
            } else if ($currencyConverter['first_currency'] == $currencyConverter['second_currency']) {
                return redirect()->back()->with('message', $currencyConverter['amount'] . ' ' . $currencyConverter['first_currency'] . ' = ' .
                                $currencyConverter['amount'] . ' ' . $currencyConverter['second_currency']);
            }
        }

        //obsługa zamiany walut gdzie łańcuch $value nie jest zgodny z wartością z API(pośrednio)
        foreach (array_values($currencies) as $currency) {
            if ($currency['product_id'] != $value) {
                //obsługa zamiany walut FIAT na inne kryptowaluty(tylko zgodne z API)
                if ($currencyConverter['first_currency'] == 'PLN' || $currencyConverter['first_currency'] == 'USD' || $currencyConverter['first_currency'] == 'EUR') {
                    $value = $currencyConverter['second_currency'] . '-' . $currencyConverter['first_currency'];
                    foreach (array_values($currencies) as $currency) {
                        if ($currency['product_id'] == $value) {
                            $price = $currencyConverter['amount'] / $currency['price'];
                            $currencyConverter['first_currency'] = $request->get('first_currency');
                            return redirect()->back()->with('message', $currencyConverter['amount'] . ' ' . $currencyConverter['first_currency'] . ' = ' .
                                            $price . ' ' . $currencyConverter['second_currency']);
                        }
                    }
                }
                //obsługa zamiany BTC na inne kryptowaluty
                if (($currencyConverter['first_currency'] == 'BTC') && ($currencyConverter['second_currency'] != 'PLN' || $currencyConverter['second_currency'] != 'USD' || $currencyConverter['second_currency'] != 'EUR')) {
                    $currencyConverter['second_currency'] = 'BTC';
                    $currencyConverter['first_currency'] = $request->get('second_currency');
                    $value = $currencyConverter['first_currency'] . '-' . $currencyConverter['second_currency']; 
                    foreach (array_values($currencies) as $currency) {
                        if ($currency['product_id'] == $value) {
                            $price = $currencyConverter['amount'] / $currency['price'];
                            $currencyConverter['first_currency'] = $request->get('first_currency');
                            $currencyConverter['second_currency'] = $request->get('second_currency');
                            return redirect()->back()->with('message', $currencyConverter['amount'] . ' ' . $currencyConverter['first_currency'] . ' = ' .
                                            $price . ' ' . $currencyConverter['second_currency']);
                        }
                    }
                }

                //obsługa zamiany między walutami FIAT
                if (($currencyConverter['first_currency'] == 'PLN' || $currencyConverter['first_currency'] == 'USD' || $currencyConverter['first_currency'] == 'EUR') &&
                        ($currencyConverter['second_currency'] == 'PLN' || $currencyConverter['second_currency'] == 'USD' || $currencyConverter['second_currency'] == 'EUR')) {
                    $currencyConverter['first_currency'] = 'BTC';
                    $value = $currencyConverter['first_currency'] . '-' . $currencyConverter['second_currency']; 
                    foreach (array_values($currencies) as $currency) {
                        if ($currency['product_id'] == $value) {
                            $var = $currencyConverter['amount'] * $currency['price'];
                            $currencyConverter['second_currency'] = $request->get('first_currency');
                            $value = $currencyConverter['first_currency'] . '-' . $currencyConverter['second_currency'];  
                            foreach (array_values($currencies) as $currency) {
                                if ($currency['product_id'] == $value) {
                                    $price = $var / $currency['price'];
                                    $currencyConverter['first_currency'] = $request->get('first_currency');
                                    $currencyConverter['second_currency'] = $request->get('second_currency');
                                    return redirect()->back()->with('message', $currencyConverter['amount'] . ' ' . $currencyConverter['first_currency'] . ' = ' .
                                                    $price . ' ' . $currencyConverter['second_currency']);
                                }
                            }
                        }
                    }
                }

                //osługa pozostałych przypadków
                $currencyConverter['second_currency'] = 'BTC';
                $value = $currencyConverter['first_currency'] . '-' . $currencyConverter['second_currency'];
                foreach (array_values($currencies) as $currency) {
                    if ($currency['product_id'] == $value) {
                        $var = $currencyConverter['amount'] * $currency['price'];
                        $currencyConverter['second_currency'] = $request->get('second_currency');
                        if ($currencyConverter['second_currency'] == 'PLN' || $currencyConverter['second_currency'] == 'USD' || $currencyConverter['second_currency'] == 'EUR') {
                            $currencyConverter['first_currency'] = 'BTC';
                            $value = $currencyConverter['first_currency'] . '-' . $currencyConverter['second_currency'];
                            foreach (array_values($currencies) as $currency) {
                                if ($currency['product_id'] == $value) {
                                    $price = $var * $currency['price'];
                                    $currencyConverter['first_currency'] = $request->get('first_currency');
                                    return redirect()->back()->with('message', $currencyConverter['amount'] . ' ' . $currencyConverter['first_currency'] . ' = ' .
                                                    $price . ' ' . $currencyConverter['second_currency']);
                                }
                            }
                        } else {
                            $currencyConverter['first_currency'] = $currencyConverter['second_currency'];
                            $currencyConverter['second_currency'] = 'BTC';
                            $value = $currencyConverter['first_currency'] . '-' . $currencyConverter['second_currency'];
                            foreach (array_values($currencies) as $currency) {
                                if ($currency['product_id'] == $value) {
                                    $price = $var / $currency['price'];
                                    $currencyConverter['first_currency'] = $request->get('first_currency');
                                    $currencyConverter['second_currency'] = $request->get('second_currency');
                                    return redirect()->back()->with('message', $currencyConverter['amount'] . ' ' . $currencyConverter['first_currency'] . ' = ' .
                                                    $price . ' ' . $currencyConverter['second_currency']);
                                }
                            }
                        }
                    }
                }
            }
        }
        return redirect()->back()->with('message', 'Error, the request can not be processed.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }

}
