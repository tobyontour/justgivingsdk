<?php

/**
 * Base model class.
 *
 * This is needed because the JustGiving REST endpoints deal with models that
 * have a lot of fields which it is difficult to manage simply.
 */

namespace JustGivingApi\Models;

/**
 * Base model class.
 *
 * The Model class contains methods for loading an array into a class's properties and
 * exporting the properties to an array. This just makes it easier to program with the
 * different structures the REST API needs whilst making it easy for the Services to convert
 * the arrays from REST calls to objects easily.
 *
 * Each child class is basically just a data structure
 */
class Model
{
    /**
     * Constructor.
     *
     * @param array An array to prepopulate the event with, often from a JSON decode response.
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                if (!empty($this->$key) && is_array($this->$key)) {
                    if (is_array($value)) {
                        $this->$key = array_merge($this->$key, $value);
                    } else {
                        $this->{$key}[] = $value;
                    }
                } else {
                    $this->$key = $value;
                }
            } else {
                // echo "Field missing: $key\n";
            }
        }
    }

    /**
     * Convert the object to an array.
     *
     * @param  array $omitList List of properties to omit.
     * @return array The array to send as part of a REST request.
     */
    public function toArray($omitList = [])
    {
        $arr = array();
        foreach (array_keys(get_class_vars(get_class($this))) as $var) {
            if (!is_null($this->$var) && !in_array($var, $omitList)) {
                $arr[$var] = $this->$var;
            }
        }
        return $arr;
    }
}
