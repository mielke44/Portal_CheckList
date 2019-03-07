sources_checklists = {
    data() {
        return {
            models: {
                checklist: {
                    routes: {
                        list: routes.checklist_list,
                        store: routes.checklist_store,
                        destroy: routes.checklist_destroy
                    },
                    list: []
                }
            }
        }
    },
}
