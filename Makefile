###############################################################################
###############################################################################
##                                                                           ##
##             PackageFactory.AtomicFusion.PresentationObjects               ##
##                                                                           ##
###############################################################################
###############################################################################

###############################################################################
#                                VARIABLES                                    #
###############################################################################
SHELL=/bin/bash

###############################################################################
#                             INSTALL & CLEANUP                               #
###############################################################################
install::
	@composer install

cleanup::
	@rm -f composer.lock
	@rm -rf Packages
	@rm -rf Build
	@rm -rf bin

###############################################################################
#                                    QA                                       #
###############################################################################
lint::
	@bin/phpcs \
		--standard=PSR2 \
		--extensions=php \
		--exclude=Generic.Files.LineLength \
		Classes/ Tests/

analyse::
	@bin/phpstan analyse \
		--autoload-file Build/BuildEssentials/PhpUnit/UnitTestBootstrap.php \
		--level 8 \
		Tests/Unit
	@bin/phpstan analyse --level 8 Classes

test::
	@bin/phpunit -c phpunit.xml \
		--enforce-time-limit \
		--coverage-html Build/Reports/coverage \
		Tests

test-isolated::
	@bin/phpunit -c phpunit.xml \
		--enforce-time-limit \
		--group isolated \
		Tests

github-action::
	@act -P ubuntu-20.04=shivammathur/node:focal
