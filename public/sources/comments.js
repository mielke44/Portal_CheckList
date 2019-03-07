sources_comments = {
    data(){
        return{
            models:{
                comment:{
                    routes:{
                        list: routes.comment_list,
                        store: routes.comment_store,
                        destroy: routes.comment_destroy
                    },
                    list:[]
                }
            }
        }
    }
}
