<?php
/*
 * This file is part of the GetOptionKit package.
 *
 * (c) Yo-An Lin <cornelius.howl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace GetOptionKit;

class OptionSpecCollection
{
    public $data = array();

    public $longOptions = array();
    public $shortOptions = array();

    function __construct()
    {
        $this->data = array();
    }

    function addFromSpecString($specString,$description = null,$key = null)
    {
        // parse spec
        $spec = new OptionSpec($specString);
        if( $description )
            $spec->description = $description;
        if( $key )
            $spec->key = $key;
        $this->add( $spec );
        return $spec;
    }

    function add($spec )
    {
        $this->data[ $spec->getId() ] = $spec;
        if( $spec->long )
            $this->longOptions[ $spec->long ] = $spec;
        if( $spec->short )
            $this->longOptions[ $spec->short ] = $spec;
        if( ! $spec->long && ! $spec->short )
            throw new Exception('Wrong option spec');
    }

    function getLongOption( $name )
    {
        return @$this->longOptions[ $name ];
    }

    function getShortOption( $name )
    {
        return @$this->shortOptions[ $name ];
    }

    /* get spec by spec id */
    function get($id)
    {
        return @$this->data[ $id ];
    }

    function getSpec($name)
    {
        if( isset($this->longOptions[ $name ] ))
            return $this->longOptions[ $name ];
        if( isset($this->shortOptions[ $name ] ))
            return $this->shortOptions[ $name ];
    }

    function size()
    {
        return count($this->data);
    }

    function all()
    {
        return $this->data;
    }

    function toArray()
    {
        $array = array();
        foreach($this->data as $k => $spec) {
            $item = array();
            if( $spec->long )
                $item['long'] = $spec->long;
            if( $spec->short )
                $item['short'] = $spec->short;
            $item['description'] = $spec->description;
            $array[] = $item;
        }
        return $array;
    }

    function printOptions( $class = 'GetOptionKit\OptionPrinter' )
    {
        $printer = new $class( $this );
        if( !( $printer instanceof \GetOptionKit\OptionPrinterInterface )) {
            throw new Exception("$class does not implement GetOptionKit\OptionPrinterInterface.");
        }
        $printer->printOptions();
    }

}
