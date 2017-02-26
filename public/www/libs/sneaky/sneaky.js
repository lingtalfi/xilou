if ('undefined' === typeof window.sneaky) {
    window.sneaky = {


        //----------------------------------------
        // SELECTOR
        //----------------------------------------
        /**
         *
         * Replace the options of a selector by the given items.
         * Items is an array which keys are the value of the options tag, and
         * which values are the labels of the options tag.
         *
         * You might want to preserve the option with value 0, with the preserveOptionZero option.
         *
         *
         * @param preserveOptionZero: bool
         */
        selectorReplaceOptions: function (jSelector, items, preserveOptionZero) {
            if (true === preserveOptionZero) {
                jSelector.find('option').each(function () {
                    if ('0' !== $(this).val()) {
                        $(this).remove();
                    }
                });
            }
            else {
                jSelector.empty();
            }

            for (var k in items) {
                jSelector.append('<option value="' + sneaky.escapeDoubleQuotes(k) + '">' + items[k] + '</option>');
            }
        },
        //----------------------------------------
        // ESCAPE
        //----------------------------------------
        /**
         * Escape double quotes, so that the returned string fit injection into a double quote wrapped
         * html attribute.
         *
         * For instance, if you want to inject a string into the value attribute of an html option tag.
         *
         *      <option value="HERE_FOR_INSTANCE"></option>
         *
         */
        // https://gist.github.com/getify/3667624
        escapeDoubleQuotes: function (str) {
            return str.replace(/\x22/g, '\\\x22');
        }
    };
}
