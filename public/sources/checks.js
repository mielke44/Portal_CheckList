sources_checks = {
    data(){
        return{
            models:{
                check:{
                    routes:{
                        store: routes.check_store,
                        list: routes.check_list,
                        destroy: routes.check_destroy
                    },
                    list:[]
                }
            }
        }
    },
    methods:{
        check_get(task_id){
            for(c of this.models.check.list){
                if(c.task_id == task_id)return c;
            }
            return null;
        },
        check_get_status(task_id){
            status = this.check_get(task_id).status
            if(status==0)return "Aberto";
            else if(status==1)return "Concluído";
            else if(status==-1)return "Expirado";
            else if(status==-2)return "Bloqueado";
        },
    }
}
