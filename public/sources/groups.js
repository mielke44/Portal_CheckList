sources_groups = {
    data(){
        return{
            models:{
                group:{
                    routes:{
                        list: routes.group_list,
                        store: routes.group_store,
                        destroy: routes.group_destroy
                    },
                    list:[],
                }
            }

        }
    },
}
