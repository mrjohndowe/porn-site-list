<?php
    print_r(getAddresses_www('google.com'));
    /* outputs Array (
      [0] => 66.11.155.215
    ) */
    print_r(getAddresses_www('example.net'));
    /* outputs Array (
      [0] => 192.0.43.10
      [1] => 2001:500:88:200::10
    ) */

    function getAddresses($domain) {
        $records = dns_get_record($domain);
        $res = array();
        foreach ($records as $r) {
            if ($r['host'] != $domain) continue; // glue entry
            if (!isset($r['type'])) continue; // DNSSec

            if ($r['type'] == 'A') $res[] = $r['ip'];
            if ($r['type'] == 'AAAA') $res[] = $r['ipv6'];
        }
        return $res;
    }

    function getAddresses_www($domain) {
      $res = getAddresses($domain);
      if (count($res) == 0) {
        $res = getAddresses('www.' . $domain);
      }
      return $res;
    }
    
?>