<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.es.min.js"></script>

<script>
    let preguntaCont = 1;

    $('#fecha').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true,
        locale: "es-ES"
    });

    function agregarPreguntaPanel() {
        const container = document.getElementById('panelPreguntasContainer');
        const index = container.children.length + 1;

        const preguntaDiv = document.createElement('div');
        preguntaDiv.classList.add('form-group', 'mt-5');
        preguntaDiv.innerHTML = `
            <label>Pregunta ${index}:</label>
            <input type="text" name="preguntas[${index}][titulo]" class="form-control" required>
            
            <div class="form-group col-md-12 mt-2">
                <label>Tipo de pregunta:</label>
                <select class="form-control" name="preguntas[${index}][tipo]" onchange="cambiarTipoPregunta(${index}, this.value)">
                    <option value="texto">Texto</option>
                    <option value="texto_abierto">Texto abierto</option>
                    <option value="opcion_unica">Opción única</option>
                    <option value="opcion_multiple">Opción múltiple</option>
                </select>
            </div>

            <div class="form-group col-md-13 mt-2">
                <label>
                    Requerida  
                    <input type="checkbox" name="preguntas[${index}][requerida]" value="1">
                </label>
            </div>

            <div id="opcionesPregunta${index}"></div>

            <button type="button" class="btn btn-danger btn-sm mt-2" onclick="this.parentNode.remove()">Eliminar</button>
        `;

        container.appendChild(preguntaDiv);
        preguntaCont++;
    }
</script>
