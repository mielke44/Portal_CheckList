sources_checks = {
    data(){
        return{
            checks:[]
        }
    },
    methods:{
        getCheckByTask: function (id) {
            for (j = 0; j < this.checklist_selected.checks.length; j++) {
                if (id == this.checklist_selected.checks[j].task_id) return this.checklist_selected.checks[j]
            }
            return null;
        },
        updateCheck: function (change_type, check_id, data,id) {
            this.dialog_responsavel = false;
            form_data = {
                check_id: check_id
            };
            switch (change_type) {
                case "RESP":
                    form_data.resp = this.form.resp;
                    this.check_tree_selected.resp = this.form.resp;
                    break;
                case "STATUS":
                    form_data.status = data.status ? 1 : 0;
                    break;
            }
            $.ajax({
                url: "{{route('check.edit')}}",
                method: "POST",
                dataType: "json",
                headers: app.headers,
                data: form_data
            }).done(response => {
                if(response['error']==false)app.notify("Tarefa modificada!", "success");
                this.list_checklist(id);
            });
        },
        count_check: function (check_id, check_status,id) {
            if (check_status) {
                this.employee_selected.check_true_size++;
            } else if (!status) {
                this.employee_selected.check_true_size--;
            }
            this.updateCheck("STATUS", check_id, {
                status: check_status
            },id);

        },
    }
}
