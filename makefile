PHP_BUILD_MODE=debug

# HTML
PUBLIC_HTML_DIR=public_html
HTML_INDEX=$(PUBLIC_HTML_DIR)/index.html


#php source
PHP_TEMPLATES != find ./templates/index -type f -name '*.php'
PHP_CONFIG=inc/config.php


#JS generated modules
JS_GENERATED_SRC_DIR=js_generated
JS_GENERATED_OUTPUT_DIR=js_src/generated_output
JS_GENERATED_OUTPUT_APP_DIR=$(JS_GENERATED_OUTPUT_DIR)/app

JS_GENERATED_APP_CONSTANTS_SRC=$(JS_GENERATED_SRC_DIR)/app/constants.js.php
JS_GENERATED_APP_CONSTANTS_OUTPUT=$(JS_GENERATED_OUTPUT_APP_DIR)/constants.js


#list of all generated js output files
JS_GENERATED_OUTPUT=$(JS_GENERATED_APP_CONSTANTS_OUTPUT)


# running webpack each time the recipe is run is technically inefficient,
# but it's the only way to not have make warnings without the dev and production css output file names being different
# also, we avoid the edge case where make won't trigger rebuild if packages in node_modules are updated by running webpack each time
all: $(JS_GENERATED_OUTPUT) $(HTML_INDEX)
	npm run webpack:dev

install:
	npm install
	mkdir -p $(JS_GENERATED_OUTPUT_APP_DIR)

#used when changing between PHP_BUILD_MODES
reset:
	rm $(HTML_INDEX)
	rm -f $(JS_GENERATED_OUTPUT)

#see comment for all: about running webpack each time recipe is called
release: PHP_BUILD_MODE=release
release: $(HTML_INDEX) $(JS_GENERATED_OUTPUT)
	npm run webpack:prod

###### PHP generated JS

$(JS_GENERATED_APP_CONSTANTS_OUTPUT): $(JS_GENERATED_APP_CONSTANTS_SRC) $(PHP_CONFIG)
	php $(JS_GENERATED_APP_CONSTANTS_SRC) > $(JS_GENERATED_APP_CONSTANTS_OUTPUT)

####### HTML

$(HTML_INDEX): $(PHP_TEMPLATES) $(PHP_CONFIG)
	php templates/index/index.php $(PHP_BUILD_MODE) > $(HTML_INDEX)