sources_employees = {
    data(){
        return{
            models:{
                employee:{
                    routes:{
                        list: routes.employee_list,
                        store: routes.employee_store,
                        destroy: routes.employee_destroy
                    },
                    list:[]
                }
            }
        }
    },

}
