#!/bin/bash

BASE=$(dirname $0)

cd $BASE;

/usr/local/bin/speedtest-cli --json | php processJsonLog.php
