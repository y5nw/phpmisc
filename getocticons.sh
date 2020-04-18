#!/bin/bash

AGREE_LICENSE=false

if [ $AGREE_LICENSE == false ]; then
	echo "Please make sure that you agree to the license of Octicons before preceeding.";
	echo "To make sure that you read this message, edit this script and change \"false\" to \"true\"";
else
	OCTICONS_DATA=$(echo "<?php var_export(json_decode('$(wget -O- https://unpkg.com/octicons/build/data.json)')); ?>" | php)
	echo "<?php define('PHPMISC_OCTICONS_DATA', ${OCTICONS_DATA}); ?>" > octicons.php
fi
