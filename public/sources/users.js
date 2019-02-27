sources_users = {
    data() {
        return {
            models: {
                user: {
                    routes: {
                        list: routes.admin_list,
                        store: routes.admin_store,
                        destroy: routes.admin_destroy
                    },
                    list: [],
                },
                group: {
                    routes: {
                        list: routes.group_list,
                        store: routes.group_store,
                        destroy: routes.group_destroy
                    },
                    list: [],
                }
            }

        }
    },
    computed:{
        user_resp_array() {
            var array = []
            array.push({
                id: 0,
                name: "Contratado"
            })
            for (u of this.models.user.list) {
                array.push({
                    id: u.id,
                    name: u.name
                })
            }
            for (g of this.models.group.list) {
                array.push({
                    id: "group"+g.id,
                    name: g.name
                })
            }
            return array;
        }
    }
}
