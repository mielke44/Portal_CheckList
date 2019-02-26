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

}
