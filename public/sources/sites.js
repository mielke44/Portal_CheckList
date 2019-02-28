sources_sites = {
    data(){
        return{
            models:{
                site:{
                    routes:{
                        list: routes.site_list,
                        store: routes.site_store,
                        destroy: routes.site_destroy
                    },
                    list:[]
                }
            }
        }
    },
    methods:{
        site_name: function (id) {
            site = this.models.site.list.find(s => s.id == id);
            if (site) return site.complete_name;
            else return "";
        },
    }
}
