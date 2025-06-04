<div class="container mx-auto p-4 mt-10">
    @includeWhen($view !== 'index',  'components.CV.back-button')

    @includeWhen($view === 'index',  'components.Vacancy.index')

    @includeWhen($view === 'create',  'components.Vacancy.create')

    @includeWhen($view === 'edit',  'components.Vacancy.edit')

    @includeWhen($view === 'show',  'components.Vacancy.show')
</div>
