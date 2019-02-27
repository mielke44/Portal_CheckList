sources_model = {
    data(){
        return{
            models:{
            }
        }
    },
    methods:{
        list_model: function (model,request,callback) {
            $.ajax({
                url: model.routes.list,
                method: "GET",
                dataType: "json",
                data: request
            }).done(response => {
                model.list = response;
                if(typeof callback != 'undefined')callback(response);
            });
        },
        store_model: function (model,data_model,callback) {
            $.ajax({
                url: model.routes.store,
                method: "POST",
                dataType: "json",
                headers: this.headers,
                data: data_model,
                success: (response) => {
                    if(typeof callback != 'undefined')callback(response);
                }
            });

        },
        destroy_model: function (model,id,callback) {
            $.ajax({
                url: model.routes.destroy,
                method: "DELETE",
                dataType: "json",
                headers: app.headers,
                data: {
                    id: id
                },
                success: (response) => {
                    if(typeof callback != 'undefined')callback(response);
                }
            });
        },
        get_model: function(model,id){
            for(i=0;i<model.list.length;i++){
                if(model.list[i].id == id)return model.list[i];
            }
            return null;
        }
    }
}
