<div class="container mx-auto p-4 mt-10">
    @includeWhen($view !== 'index',  'components.CV.back-button')

    @includeWhen($view === 'index',  'components.CV.index')

    @includeWhen($view === 'create',  'components.CV.create')

    @includeWhen($view === 'edit',  'components.CV.edit')

    @includeWhen($view === 'show',  'components.CV.show')
</div>
