<?php

/**
 * Base model class.
 *
 * This is needed because the JustGiving REST endpoints deal with models that
 * have a lot of fields which it is difficult to manage simply.
 */

namespace JustGivingApi\Models;

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
            $key = str_replace('.', '_', $key);
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Convert the object to an array.
     *
     * @return array The array to send as part of a REST request.
     */
    public function toArray()
    {
        $arr = array();
        foreach (array_keys(get_class_vars(get_class($this))) as $var) {
            $key = str_replace('_', '.', $var);
            if (!is_null($this->$var)) {
                $arr[$key] = $this->$var;
            }
        }
        return $arr;
    }
}
