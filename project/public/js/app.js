// Для форм с зависимыми select
document.addEventListener('DOMContentLoaded', function() {
    // Загрузка моделей при изменении марки
    document.querySelectorAll('#brand_id').forEach(select => {
        select.addEventListener('change', function() {
            const brandId = this.value;
            const modelSelect = this.closest('form').querySelector('#car_model_id');
            
            if (brandId) {
                fetch(`/admin/models/get-by-brand?brand_id=${brandId}`)
                    .then(response => response.json())
                    .then(models => {
                        modelSelect.innerHTML = '<option value="">Выберите модель</option>';
                        models.forEach(model => {
                            const option = document.createElement('option');
                            option.value = model.id;
                            option.textContent = model.name;
                            modelSelect.appendChild(option);
                        });
                    });
            } else {
                modelSelect.innerHTML = '<option value="">Выберите модель</option>';
                const generationSelect = this.closest('form').querySelector('#generation_id');
                if (generationSelect) {
                    generationSelect.innerHTML = '<option value="">Выберите поколение</option>';
                }
            }
        });
    });
    
    // Загрузка поколений при изменении модели
    document.querySelectorAll('#car_model_id').forEach(select => {
        select.addEventListener('change', function() {
            const modelId = this.value;
            const generationSelect = this.closest('form').querySelector('#generation_id');
            
            if (modelId && generationSelect) {
                fetch(`/admin/generations/get-by-model?model_id=${modelId}`)
                    .then(response => response.json())
                    .then(generations => {
                        generationSelect.innerHTML = '<option value="">Выберите поколение</option>';
                        generations.forEach(generation => {
                            const option = document.createElement('option');
                            option.value = generation.id;
                            option.textContent = `${generation.name} (${generation.year_from}${generation.year_to ? '-' + generation.year_to : ''})`;
                            generationSelect.appendChild(option);
                        });
                    });
            } else if (generationSelect) {
                generationSelect.innerHTML = '<option value="">Выберите поколение</option>';
            }
        });
    });
    
    // Инициализация при загрузке страницы редактирования
    document.querySelectorAll('#brand_id').forEach(select => {
        if (select.value) {
            select.dispatchEvent(new Event('change'));
        }
    });
});