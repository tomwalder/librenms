<?php
/*
 * LibreNMS module to Graph Cisco IPSLA UDP Jitter metrics
 *
 * Copyright (c) 2016 Aaron Daniels <aaron@daniels.id.au>
 *
 * This program is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation, either version 3 of the License, or (at your
 * option) any later version.  Please see LICENSE.txt at the top level of
 * the source code distribution for details.
 */

$sla = dbFetchRow('SELECT `sla_nr` FROM `slas` WHERE `sla_id` = ?', array($vars['id']));

require 'includes/graphs/common.inc.php';
$rrd_options .= ' -l 0 -E ';
$rrd_filename = rrd_name($device['hostname'], array('sla', $sla['sla_nr'], 'jitter'));

if (file_exists($rrd_filename)) {
    $rrd_options .= " COMMENT:'                            Cur   Min  Max\\n'";

    $rrd_options .= " DEF:ICPIF=" . $rrd_filename . ":ICPIF:AVERAGE ";
    $rrd_options .= " LINE1.25:ICPIF#0000ee:'ICPIF                     ' ";
    $rrd_options .= " GPRINT:ICPIF:LAST:%3.0lf ";
    $rrd_options .= " GPRINT:ICPIF:MIN:%3.0lf ";
    $rrd_options .= " GPRINT:ICPIF:MAX:%3.0lf\\\l ";
}
