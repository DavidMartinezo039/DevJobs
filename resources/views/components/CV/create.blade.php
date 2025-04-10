<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-2xl font-bold text-center my-10">{{ __('Create CV') }}</h1>
                <div class="md:flex md:justify-center p-5">
                    <div class="md:w-1/2 space-y-5">
                        <x-forms.input id="title" name="title" label="Title" wire:model="title"/>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-center my-10">{{ __('Personal Data') }}</h2>
                <div class="md:flex md:justify-center p-5">
                    <div class="md:w-1/2 space-y-5">
                        <x-forms.input id="first_name" name="first_name" label="First Name"
                                       wire:model="first_name"/>
                        <x-forms.input id="last_name" name="last_name" label="Last Name"
                                       wire:model="last_name"/>
                        <div>
                            <x-input-label for="image" :value="__('Profile Picture')"/>
                            <input type="file" id="image" wire:model="image"
                                   class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full">
                            <x-input-error :messages="$errors->get('image')" class="mt-2"/>

                            <!-- Mostrar vista previa de la imagen subida -->
                            @if ($image)
                                <img src="{{ $image->temporaryUrl() }}" class="mt-2 max-h-40">
                            @elseif ($selectedCv && $selectedCv->personalData && $selectedCv->personalData->image)
                                <img src="{{ Storage::url($selectedCv->personalData->image) }}"
                                     class="mt-2 max-h-40">
                            @endif
                        </div>

                        <div>
                            <x-input-label for="about_me" :value="__('About Me')"/>
                            <textarea wire:model="about_me" placeholder="About Me"
                                      class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
                                      focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500
                                      dark:focus:ring-indigo-600 rounded-md shadow-sm w-full h-36"></textarea>
                            <x-input-error :messages="$errors->get('about_me')" class="mt-2"/>
                        </div>

                        <x-forms.input id="birth_date" type="date" name="birth_date" label="Birth Date"
                                       wire:model="birth_date"/>
                        <x-forms.input id="city" name="city" label="City" wire:model="city"/>
                        <x-forms.input id="country" name="country" label="Country" wire:model="country"/>

                        <div>
                            <x-input-label :value="__('Nationalities')"/>
                            @foreach ($nationalities as $index => $nationality)
                                <div class="flex space-x-2 mt-2">
                                    <input type="text" wire:model="nationalities.{{ $index }}"
                                           placeholder="Enter nationality" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
                          dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full">
                                    <button type="button" wire:click="removeNationality({{ $index }})"
                                            class="bg-red-500 text-white px-3 py-1 rounded">X
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addNationality"
                                    class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">+ Add Nationality
                            </button>
                        </div>

                        <div>
                            <x-input-label :value="__('Work Permits')"/>
                            @foreach ($workPermits as $index => $permit)
                                <div class="flex space-x-2 mt-2">
                                    <input type="text" wire:model="workPermits.{{ $index }}" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
                          dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full"
                                           placeholder="Enter work permit">
                                    <button type="button" wire:click="removeWorkPermit({{ $index }})"
                                            class="bg-red-500 text-white px-3 py-1 rounded">X
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addWorkPermit"
                                    class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">+ Add Work Permit
                            </button>
                        </div>

                        <div>
                            <x-input-label for="emails" :value="__('Emails')"/>

                            @foreach ($emails as $index => $email)
                                <div class="flex space-x-2 mt-2">
                                    <input type="email" wire:model="emails.{{ $index }}"
                                           class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500
                          dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full"
                                           placeholder="example@mail.com">

                                    <button type="button" wire:click="removeEmail({{ $index }})"
                                            class="bg-red-500 text-white px-3 py-1 rounded">X
                                    </button>
                                </div>
                            @endforeach

                            <button type="button" wire:click="addEmail"
                                    class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">
                                + {{ __('Add Email') }}</button>

                            <x-input-error :messages="$errors->get('emails')" class="mt-2"/>
                        </div>


                        <div>
                            <x-input-label for="addresses" :value="__('Addresses')"/>
                            @foreach ($addresses as $index => $address)
                                <div class="flex space-x-2 mt-2">
                                    <input type="text" wire:model="addresses.{{ $index }}"
                                           placeholder="Enter address"
                                           class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full">
                                    <button type="button" wire:click="removeAddress({{ $index }})"
                                            class="bg-red-500 text-white px-3 py-1 rounded">X
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addAddress"
                                    class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">
                                + {{ __('Add Address') }}</button>
                        </div>

                        {{-- GÉNERO --}}
                        <div>
                            <x-input-label for="gender_id" :value="__('Gender')"/>
                            <select id="gender_id" wire:model="gender_id"
                                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full">
                                <option value="">{{ __('Select Gender') }}</option>
                                @foreach ($genders as $gender)
                                    <option value="{{ $gender->id }}">{{ $gender->type }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('gender_id')" class="mt-2"/>
                        </div>

                        {{-- DOCUMENTOS DE IDENTIDAD --}}
                        <div>
                            <x-input-label :value="__('Identity Documents')"/>
                            @foreach ($identity_documents as $index => $identity)
                                <div class="flex space-x-2 mt-2">
                                    <select wire:model="identity_documents.{{ $index }}.identity_id"
                                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-1/3">
                                        <option value="">{{ __('Select Type') }}</option>
                                        @foreach ($identityTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->type }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" wire:model="identity_documents.{{ $index }}.number"
                                           placeholder="Document Number"
                                           class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full">
                                    <button type="button" wire:click="removeIdentity({{ $index }})"
                                            class="bg-red-500 text-white px-3 py-1 rounded">X
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addIdentity"
                                    class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">
                                + {{ __('Add Identity') }}</button>
                        </div>

                        {{-- TELÉFONOS --}}
                        <div>
                            <x-input-label :value="__('Phone Numbers')"/>
                            @foreach ($phones as $index => $phone)
                                <div class="flex space-x-2 mt-2">
                                    <select wire:model="phones.{{ $index }}.phone_id"
                                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-1/3">
                                        <option value="">{{ __('Select Type') }}</option>
                                        @foreach ($phoneTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->type }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" wire:model="phones.{{ $index }}.number"
                                           placeholder="Enter phone number"
                                           class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full">
                                    <button type="button" wire:click="removePhone({{ $index }})"
                                            class="bg-red-500 text-white px-3 py-1 rounded">X
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addPhone"
                                    class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">
                                + {{ __('Add Phone') }}</button>
                        </div>

                        {{-- REDES SOCIALES --}}
                        <div>
                            <x-input-label :value="__('Social Media')"/>
                            @foreach ($socialMedia as $index => $social)
                                <div class="flex space-x-2 mt-2">
                                    <select wire:model="socialMedia.{{ $index }}.social_media_id"
                                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-1/3">
                                        <option value="">{{ __('Select Platform') }}</option>
                                        @foreach ($socialMediaTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->type }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" wire:model="socialMedia.{{ $index }}.user_name"
                                           placeholder="Username"
                                           class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full">
                                    <input type="url" wire:model="socialMedia.{{ $index }}.url"
                                           placeholder="URL"
                                           class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full mt-1">
                                    <button type="button" wire:click="removeSocialMedia({{ $index }})"
                                            class="bg-red-500 text-white px-3 py-1 rounded mt-1">X
                                    </button>
                                </div>
                            @endforeach
                            <button type="button" wire:click="addSocialMedia"
                                    class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">
                                + {{ __('Add Social Media') }}</button>
                        </div>

                        <div class="space-y-6">
                            {{-- Selector de secciones --}}
                            <div>
                                <label class="font-semibold">Añadir sección:</label>
                                <select wire:change="addSection($event.target.value)" class="form-select">
                                    <option value="">-- Selecciona --</option>
                                    @foreach ($availableSections as $key => $label)
                                        @if (!in_array($key, $activeSections))
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            {{-- Experiencia Laboral --}}
                            @if (in_array('work_experience', $activeSections))
                                <div class="border p-4 rounded bg-gray-50">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="font-bold">Experiencia Laboral</h3>
                                        <button wire:click="removeSection('work_experience')"
                                                class="text-red-500">Eliminar sección
                                        </button>
                                    </div>
                                    @foreach ($workExperiences as $index => $item)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                                            <input wire:model="workExperiences.{{ $index }}.company"
                                                   placeholder="Empresa" class="form-input">
                                            <input wire:model="workExperiences.{{ $index }}.position"
                                                   placeholder="Puesto" class="form-input">
                                            <input wire:model="workExperiences.{{ $index }}.start"
                                                   placeholder="Inicio" class="form-input">
                                            <input wire:model="workExperiences.{{ $index }}.end"
                                                   placeholder="Fin" class="form-input">
                                            <textarea wire:model="workExperiences.{{ $index }}.description"
                                                      placeholder="Descripción"
                                                      class="form-textarea md:col-span-2"></textarea>
                                        </div>
                                        <button wire:click="removeEntry('work_experience', {{ $index }})"
                                                class="text-red-500 text-sm mb-2">Eliminar experiencia
                                        </button>
                                    @endforeach
                                    <button wire:click="addEntry('work_experience')"
                                            class="bg-blue-500 text-white px-3 py-1 rounded">+ Añadir
                                        experiencia
                                    </button>
                                </div>
                            @endif

                            {{-- Educación --}}
                            @if (in_array('education', $activeSections))
                                <div class="border p-4 rounded bg-gray-50">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="font-bold">Educación</h3>
                                        <button wire:click="removeSection('education')" class="text-red-500">
                                            Eliminar sección
                                        </button>
                                    </div>
                                    @foreach ($educations as $index => $item)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                                            <input wire:model="educations.{{ $index }}.school"
                                                   placeholder="Centro educativo" class="form-input">
                                            <input wire:model="educations.{{ $index }}.degree"
                                                   placeholder="Título" class="form-input">
                                            <input wire:model="educations.{{ $index }}.start"
                                                   placeholder="Inicio" class="form-input">
                                            <input wire:model="educations.{{ $index }}.end" placeholder="Fin"
                                                   class="form-input">
                                            <textarea wire:model="educations.{{ $index }}.description"
                                                      placeholder="Descripción"
                                                      class="form-textarea md:col-span-2"></textarea>
                                        </div>
                                        <button wire:click="removeEntry('education', {{ $index }})"
                                                class="text-red-500 text-sm mb-2">Eliminar educación
                                        </button>
                                    @endforeach
                                    <button wire:click="addEntry('education')"
                                            class="bg-blue-500 text-white px-3 py-1 rounded">+ Añadir educación
                                    </button>
                                </div>
                            @endif

                            {{-- Idiomas --}}
                            @if (in_array('languages', $activeSections))
                                <div class="border p-4 rounded bg-gray-50">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="font-bold">Idiomas</h3>
                                        <button wire:click="removeSection('languages')" class="text-red-500">
                                            Eliminar sección
                                        </button>
                                    </div>
                                    @foreach ($languages as $index => $item)
                                        <div class="flex items-center space-x-2 mb-2">
                                            <input wire:model="languages.{{ $index }}.language"
                                                   placeholder="Idioma" class="form-input w-1/2">
                                            <input wire:model="languages.{{ $index }}.level" placeholder="Nivel"
                                                   class="form-input w-1/2">
                                            <button wire:click="removeEntry('languages', {{ $index }})"
                                                    class="text-red-500">✕
                                            </button>
                                        </div>
                                    @endforeach
                                    <button wire:click="addEntry('languages')"
                                            class="bg-blue-500 text-white px-3 py-1 rounded">+ Añadir idioma
                                    </button>
                                </div>
                            @endif

                            {{-- Habilidades --}}
                            @if (in_array('skills', $activeSections))
                                <div class="border p-4 rounded bg-gray-50">
                                    <div class="flex justify-between items-center mb-2">
                                        <h3 class="font-bold">Habilidades</h3>
                                        <button wire:click="removeSection('skills')" class="text-red-500">
                                            Eliminar sección
                                        </button>
                                    </div>
                                    @foreach ($skills as $index => $item)
                                        <div class="flex items-center space-x-2 mb-2">
                                            <input wire:model="skills.{{ $index }}.name" placeholder="Habilidad"
                                                   class="form-input w-1/2">
                                            <input wire:model="skills.{{ $index }}.level" placeholder="Nivel"
                                                   class="form-input w-1/2">
                                            <button wire:click="removeEntry('skills', {{ $index }})"
                                                    class="text-red-500">✕
                                            </button>
                                        </div>
                                    @endforeach
                                    <button wire:click="addEntry('skills')"
                                            class="bg-blue-500 text-white px-3 py-1 rounded">+ Añadir habilidad
                                    </button>
                                </div>
                            @endif
                        </div>

                        <x-primary-button wire:click="store">{{ __('Create CV') }}</x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
