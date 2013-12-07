/*
 * Redactor emoticon plugin.
 * Copyright (c) 2013 Tommy Brunn (tommy.brunn@gmail.com)
 * https://github.com/Nevon/redactor-emoticons
 */
var RLANG = {};

if (typeof RedactorPlugins === 'undefined') {
    var RedactorPlugins = {};
}

RedactorPlugins.emoticons = {
    init: function() {
        "use strict";
        if (typeof(RLANG.emoticons) === 'undefined') {
            RLANG.emoticons = 'Insert emoticon';
        }
        if (typeof(RLANG.emoticons_help) === 'undefined') {
            RLANG.emoticons_help = 'Hover over an emoticon to see its shortcode. Type in the shortcode, select the text and press the emoticon button to convert it automatically.';
        }

        var that = this;

        // choose the view type: modal window or dropdown box
        switch (this.opts.emoticons.viewType) {
            case 'dropdown':
                var mylist = {};
                for (var i = 0; i < this.opts.emoticons.items.length; i++) {
                    mylist[this.opts.emoticons.items[i].name] = {
                        title: '<img data-code="' + this.opts.emoticons.items[i].shortcode + '" src="' + this.opts.emoticons.items[i].src + '" alt="' + this.opts.emoticons.items[i].name + '" title="' + this.opts.emoticons.items[i].shortcode + '" style="cursor:pointer;">',
                        callback: function(buttonName, buttonDOM, buttonObj) {
                            that.chooseSmile(buttonName, buttonDOM, buttonObj);
                        },
                        className: 'redactor_smile'
                    }
                }
                this.buttonAdd('emoticons', RLANG.emoticons, null, mylist
                );
                break
            default:
                this.buttonAdd('emoticons', RLANG.emoticons, function() {
                    if (that.replaceSmileys() === 0) {
                        that.createModal();
                    }
                });
        }



        //Add a separator before the button
        this.buttonAddSeparatorAfter('emoticons');

        //Add icon to button
        $('a.redactor_btn_emoticons').css({
            backgroundImage: ' url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3QIXFAkhU/45rAAAAlNJREFUSMftVT1oU1EYPefF16QxKkkKkZqWd2+wwQhWEAQnB9G1FQfRzVF0UxfxZ7CDs1IRHYq6VEVRRCg4iRWnUAdbMZT70lpTWtGKYPPSJ+9zeS0lWH3VSem3XO695/sO5+O75wJr8d8Ho4Acx9lL8gjJbhHZCGAGwIsgCAYmJiaqv8qN/abwpnQ6fZfkSZLPReS2iNyzLOstgJ0k+zOZjMzNzb1ctYJisZjyfX9YRF57nnd8enq63owpFAqFIAgekxwyxpxeVe+UUreUUoPh1iqVSnaTuni45pRS77XWvT+rY63Qmi4APbZtnwjJhuv1+lR7e3smvD9gWdY3rXVftVqdEZFTItIXmcCyrF6STyqVyqfwKA4gnkgkYgBA0g5z4wCQzWYfktyilNoaiQBAUURGliYhFtvT0tKSN8Z8BADXdZ96ntdmjDkDAOVy+buIjALoai60bgWCOIDG4mZ8fHwBwMJyQK1W+9yU4wFIRFIgIh9IqkVMPp9PrDRpS+NIahGZitqiZyLSA8DSWp+3bftBLpdLLgd0dnZu833/ndZ6l+M43QDS2Wy2HPUdUGs9IiI35+fnB5LJ5B0Au0kOApgVke0ADpI8Z4zp11oPicgr13UvRlUgJI+RvJRKpfa7rnsIwFEACyKiSI6KyA5jzDWt9VURSXued3nVXhR60H2SjwBcMca8AYBSqWQ3Go19QRBcCKG9ruvO/pHZOY6TI3mW5GERWU/yq4i0AagAuN7a2npjbGzM/ys3XcR2dHRsjsViG4IgmJ2cnPyy9hn9G/EDW0nlPvgF9JwAAAAASUVORK5CYII=)'
        });

        $('img').css({
            'cursor': 'pointer'
        });
    },
    createModal: function() {
        "use strict";
        var modal = '<div id="emoticon_drawer" style="padding: 10px;width:464px;">';
        var sets = this.opts.emoticons.sets;

        console.log(sets);

        // add the tabs
        var tabs = $('<ul id="emoticon_tabs"></ul>');

        for (var i = 0; i < sets.length; i++) {


            tabs.append(
                $('<li data-val="' + i + '" class="redactor_emoticons_setbutton">' + sets[i].name + '</li>').css({
                    'display' : 'inline-block',
                    'list-style' : 'none',
                    'padding' : '1px 7px',
                    'background' : 'rgb(104, 104, 104)',
                    'border-radius' : '4px',
                    'text-shadow' : '1px 1px rgb(29, 29, 29)',
                    'color' : 'rgb(218, 218, 218)',
                    'margin-left' : '5px',
                    'cursor': 'pointer'
                })
            );

        }

        $('.redactor_emoticons_setbutton').on('click', function(){

            console.log('clicked');
            console.log( $('#redactor_emoticons_set' + $(this).attr('data-val')).html());
            $('#redactor_emoticons_set' + $(this).attr('data-val')).show();

        });

        modal += tabs.html();

        for (var i = 0; i < sets.length; i++) {
            modal += '<ul style="margin: 0; padding: 0;" id="redactor_emoticons_set' + i + '">';
            for (var j = 0; j < sets[i].items.length; j++) {
                modal += '<li style="display: inline-block; padding: 5px;width:' + this.opts.emoticons.width + '"><img src="' + sets[i].items[j].src + '" alt="' + sets[i].items[j].name + '" title="' + sets[i].items[j].shortcode + '" style="cursor:pointer;"></li>';
            }
            modal += '</ul>';
        }

        modal += '<small class="redactor-emoticon-help">' + RLANG.emoticons_help + '</small>';
        modal += '</div>';


        // HANDLER FOR IMAGE SETS


        var that = this;
        this.modalInit(RLANG.emoticons, modal, 464, function() {
            $('#emoticon_drawer img').click(function() {
                that.bufferSet();
                that.insertHtml('<img src="' + $(this).attr('src') + '" alt="' + $(this).attr('alt') + '">');
                that.modalClose();
            });
        });
    },
    /*
     * @param redactor Redactor instance
     * @return int The number of smilies replaced
     */
    replaceSmileys: function() {
        "use strict";
        var html = this.getSelectionHtml();
        var numberOfMatches = 0;

        //Replace all smileys within selected text.
        for(var i = 0; i < this.opts.emoticons.sets.length; i++) {
            for (var j = 0; j < this.opts.emoticons.sets[i].items.length; j++) {
                //Take the shortcode and escape any characters that have
                //special meaning in regexp.
                var smileyStr = (this.opts.emoticons.sets[i].items[j].shortcode + '').replace(/([\\\.\+\*\?\[\^\]\$\(\)\{\}\=\!\<\>\|\:])/g, "\\$1");
                var pattern = new RegExp('(' + smileyStr + ')', 'g');

                //Perform the match twice. Once to count number of
                //occurrences, and once to do the replace.
                numberOfMatches += (html.match(pattern) || []).length;
                html = html.replace(pattern, '<img src="' + this.opts.emoticons.sets[i].items[j].src + '" alt="' + this.opts.emoticons.sets[i].items[j].name + '">');
            }
        }


        this.insertHtml(html);

        return numberOfMatches;
    },
    chooseSmile: function(buttonName, buttonDOM, buttonObj) {
        var imgObj = buttonDOM.find('img');
        this.bufferSet();
        this.insertHtml('<img class="smile" src="' + imgObj.attr('src') + '" alt="' + imgObj.attr('alt') + '">');
        this.modalClose();
    }
};