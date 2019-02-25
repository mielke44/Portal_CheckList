sources_checklists = {
    data(){
        return{
            checklists:[]
        }
    },
    methods:{
        list_checklist: function (employee_id) {
            $.ajax({
                url: "{{route('checklist.employee')}}",
                method: "GET",
                dataType: "json",
                data: {
                    id: employee_id
                }
            }).done(response => {
                this.checklists[employee_id] = response;
                this.$forceUpdate();
            });
        },
        destroy_checklist: function (id) {
            app.confirm("Remover lista de tarefa?",
                "Todas as informações dessa lista serão deletadas.", "red", () => {
                    $.ajax({
                        url: "{{route('checklist.employee.remove')}}",
                        method: "DELETE",
                        dataType: "json",
                        headers: app.headers,
                        data: {
                            checklist_id: id
                        },
                        success: (response) => {
                            this.list_checklist(this.employee_selected.id);
                            this.list();
                            app.notify("Lista de tarefas removida", "error");
                        }
                    });
                })

        },
        checklistTT: function (id) {
            $.ajax({
                url: "{{route('checklist_store')}}",
                method: "POST",
                dataType: "json",
                headers: app.headers,
                data: {
                    employee_id: id,
                    checklist_template_id: this.form.checklist_template_id,
                },
                error: (response) => {
                    alert(JSON.stringify(response));
                    app.notify('Ocorreu um erro! Tente novamente!','error');
                },
                success: (response) => {
                    this.list_checklist(id);
                    this.list();
                }
            });
        },
    }
}
