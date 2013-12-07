/**
 * User: Maxim
 * Date: 26/03/13
 * Time: 15:49
 */
API = {
    constants: {
        'api' : "http://enayet.co.uk/api"
    },
    //get every news post ordered by date
    news : function(){
        $.post(API.api + "/news/all", function(data) {

        }
    },

    //get news by using it's id
    news : function(id) {

    }
}
