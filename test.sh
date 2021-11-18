#!/bin/bash
ARG="${1:-.}"
cd tests && ../vendor/bin/phpunit "$ARG"