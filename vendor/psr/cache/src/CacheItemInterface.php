<?php

namespace ConfigTransformer202203132\Psr\Cache;

/**
 * CacheItemInterface defines an interface for interacting with objects inside a cache.
 *
 * Each Item object MUST be associated with a specific key, which can be set
 * according to the implementing system and is typically passed by the
 * Cache\CacheItemPoolInterface object.
 *
 * The Cache\CacheItemInterface object encapsulates the storage and retrieval of
 * cache items. Each Cache\CacheItemInterface is generated by a
 * Cache\CacheItemPoolInterface object, which is responsible for any required
 * setup as well as associating the object with a unique Key.
 * Cache\CacheItemInterface objects MUST be able to store and retrieve any type
 * of PHP value defined in the Data section of the specification.
 *
 * Calling Libraries MUST NOT instantiate Item objects themselves. They may only
 * be requested from a Pool object via the getItem() method.  Calling Libraries
 * SHOULD NOT assume that an Item created by one Implementing Library is
 * compatible with a Pool from another Implementing Library.
 */
interface CacheItemInterface
{
    /**
     * Returns the key for the current cache item.
     *
     * The key is loaded by the Implementing Library, but should be available to
     * the higher level callers when needed.
     *
     * @return string
     *   The key string for this cache item.
     */
    public function getKey() : string;
    /**
     * Retrieves the value of the item from the cache associated with this object's key.
     *
     * The value returned must be identical to the value originally stored by set().
     *
     * If isHit() returns false, this method MUST return null. Note that null
     * is a legitimate cached value, so the isHit() method SHOULD be used to
     * differentiate between "null value was found" and "no value was found."
     *
     * @return mixed
     *   The value corresponding to this cache item's key, or null if not found.
     */
    public function get();
    /**
     * Confirms if the cache item lookup resulted in a cache hit.
     *
     * Note: This method MUST NOT have a race condition between calling isHit()
     * and calling get().
     *
     * @return bool
     *   True if the request resulted in a cache hit. False otherwise.
     */
    public function isHit() : bool;
    /**
     * Sets the value represented by this cache item.
     *
     * The $value argument may be any item that can be serialized by PHP,
     * although the method of serialization is left up to the Implementing
     * Library.
     *
     * @param mixed $value
     *   The serializable value to be stored.
     *
     * @return static
     *   The invoked object.
     */
    public function set($value);
    /**
     * Sets the expiration time for this cache item.
     *
     * @param ?\DateTimeInterface $expiration
     *   The point in time after which the item MUST be considered expired.
     *   If null is passed explicitly, a default value MAY be used. If none is set,
     *   the value should be stored permanently or for as long as the
     *   implementation allows.
     *
     * @return static
     *   The called object.
     */
    public function expiresAt(?\DateTimeInterface $expiration);
    /**
     * Sets the expiration time for this cache item.
     *
     * @param int|\DateInterval|null $time
     *   The period of time from the present after which the item MUST be considered
     *   expired. An integer parameter is understood to be the time in seconds until
     *   expiration. If null is passed explicitly, a default value MAY be used.
     *   If none is set, the value should be stored permanently or for as long as the
     *   implementation allows.
     *
     * @return static
     *   The called object.
     */
    public function expiresAfter($time);
}
