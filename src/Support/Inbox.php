<?php

namespace LeNats\Support;

class Inbox
{
    public static function newInbox(string $prefix = '_INBOX.'): string
    {
        return uniqid($prefix, false);
    }

    public static function getDiscoverSubject(string $clusterId): string
    {
        return '_STAN.discover.' . $clusterId;
    }
}
