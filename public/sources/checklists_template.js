

sources_checklists_template = {
    data() {
        return {
            models: {
                template: {
                    routes: {
                        list: routes.template_list,
                        store: routes.template_store,
                        destroy: routes.template_destroy
                    },
                    list: []
                }
            }
        }
    },
    methods:{
        template_tree(id,callback){
            $.ajax({
                url: routes.template_array,
                method: "GET",
                dataType: "json",
                data: {
                    id:id
                }
            }).done(response => {
                callback(response);
            });
        }
    }
}
