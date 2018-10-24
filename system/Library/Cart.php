<?php

namespace Sys\Library;

class Cart {
    
    private static $items = [];
    private static $items_num = 0;
    private static $tax_total = 0;
    private static $total = 0;

    public function __construct() {
        
        self::$items = get_session('cart');
        if(self::$items) {
            foreach (self::$items as $cart_item) {
                self::$items_num += $cart_item['qty'];
                self::$tax_total += (($cart_item['qty']*($cart_item['tax']/100))*$cart_item['price']);
                self::$total += ($cart_item['qty']*$cart_item['price']);
            }
        }
        
    }
    
    
    
    public static function add($item) {
        
        $key = md5($item['id']. implode('',$item['options']));
        if(!self::$items) {
            $cart_item = [
                $key => [
                    'id'        => $item['id'],
                    'name'      => $item['name'],
                    'price'     => $item['price'],
                    'qty'       => $item['qty'],
                    'options'   => $item['options'],
                    'tax'       => $item['tax'],
                    'image'     => $item['image'],
                    'slug'      => $item['slug'],
                    'category'  => $item['category'],
                    'shipping_price'    => $item['shipping_price'],
                    'desi_value'        => $item['desi_value'],
                    'shipping_method'   => $item['shipping_method'],
                ]
            ];
            
            self::$items = $cart_item;
            
        } else {
            
            if(isset(self::$items[$key])) {
                self::$items[$key]['qty'] += $item['qty'];
            } else {
                self::$items[$key] = [
                    'id'        => $item['id'],
                    'name'      => $item['name'],
                    'price'     => $item['price'],
                    'qty'       => $item['qty'],
                    'options'   => $item['options'],
                    'tax'       => $item['tax'],
                    'image'     => $item['image'],
                    'slug'      => $item['slug'],
                    'category'  => $item['category'],
                    'shipping_price'    => $item['shipping_price'],
                    'desi_value'        => $item['desi_value'],
                    'shipping_method'   => $item['shipping_method'],
                ];
            }
        }
        self::$items_num = self::$items_num + $item['qty'];
        self::$total = self::$total + $item['qty']*$item['price'];
        set_session('cart',self::$items);
    }
    
    public static function delete_item($key) {
        unset(self::$items[$key]);
        set_session('cart',self::$items);
    }
    
    public static function destroy() {
        self::$items = [];
        unset_session('cart');
    }
    
    public static function items() {
        return self::$items;
    }
    
    public static function items_num() {
        return self::$items_num;
    }
    
    public static function cart_total() {
        return self::$total;
    }
    
    public static function tax_total() {
        return self::$tax_total;
    }
    
}
