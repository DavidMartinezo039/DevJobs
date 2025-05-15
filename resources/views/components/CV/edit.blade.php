<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-2xl font-bold text-center my-10">{{ __('Create CV') }}</h1>
                <div class="md:flex md:justify-center p-5">
                    <div class="md:w-1/2 space-y-5">
                        <x-forms.input id="title" name="title" label="Title" wireModel="title"/>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-center my-10">{{ __('Personal Data') }}</h2>
                <div class="md:flex md:justify-center p-5">
                    <div class="md:w-1/2 space-y-5">
                        <x-forms.input id="first_name" name="first_name" label="First Name"
                                       wireModel="first_name"/>
                        <x-forms.input id="last_name" name="last_name" label="Last Name"
                                       wireModel="last_name"/>

                        <div>
                            <x-input-label for="image" :value="__('Profile Picture')" />
                            <x-text-input id="image" class="block mt-1 w-full" type="file" wire:model="new_image" accept="image/*"/>

                            <div class="my-5 w-80">
                                <x-input-label :value="__('Current Profile Picture')"/>
                                <img src="{{ asset('storage/images/' . $image) }}" alt="{{ __('Profile Image') . $title }}">
                            </div>
                            <div class="my-5 w-80">
                                @if($new_image)
                                <x-input-label :value="__('New Image')"/>
                                <img src="{{ $new_image->temporaryUrl() }}" alt="Image">
                                @endif
                            </div>
                            <x-input-error :messages="$errors->get('new_image')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="about_me" :value="__('About Me')"/>
                            <textarea wire:model="about_me" placeholder="About Me"
                                      class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300
                                      focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500
                                      dark:focus:ring-indigo-600 rounded-md shadow-sm w-full h-36"></textarea>
                            <x-input-error :messages="$errors->get('about_me')" class="mt-2"/>
                        </div>

                        <x-forms.date-picker id="birth_date" type="date" name="birth_date" label="Birth Date"
                                             wireModel="birth_date"/>
                        <x-forms.input id="city" name="city" label="City" wireModel="city"/>
                        <x-forms.input id="country" name="country" label="Country" wireModel="country"/>

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
                                           placeholder="{{  __('Document Number') }}"
                                           class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full">
                                    <button type="button" wire:click="removeIdentity({{ $index }})"
                                            class="bg-red-500 text-white px-3 py-1 rounded">X
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('identity_documents.' . $index . '.number')"
                                               class="mt-2"/>
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
                                           placeholder="{{ __('Phone number') }}"
                                           class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full">
                                    <button type="button" wire:click="removePhone({{ $index }})"
                                            class="bg-red-500 text-white px-3 py-1 rounded">X
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('phones.' . $index . '.number')" class="mt-2"/>
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
                                           placeholder="{{ __('Username') }}"
                                           class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full">
                                    <input type="url" wire:model="socialMedia.{{ $index }}.url"
                                           placeholder="URL"
                                           class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-full">
                                    <button type="button" wire:click="removeSocialMedia({{ $index }})"
                                            class="bg-red-500 text-white px-3 py-1 rounded">X
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('socialMedia.' . $index . '.user_name')"
                                               class="mt-2"/>
                                <x-input-error :messages="$errors->get('socialMedia.' . $index . '.url')" class="mt-2"/>
                            @endforeach
                            <button type="button" wire:click="addSocialMedia"
                                    class="mt-2 bg-blue-500 text-white px-4 py-2 rounded">
                                + {{ __('Add Social Media') }}</button>
                        </div>

                        <div class="space-y-6">

                            {{-- Experiencia Laboral --}}
                            @if (in_array('work_experience', $activeSections))
                                <div class="border border-gray-200 shadow-md p-6 rounded-2xl bg-gray-100">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="font-bold text-xl flex items-center gap-2">
                                            {{ __('Work Experience') }}
                                        </h3>
                                        <button wire:click="removeSection('work_experience')"
                                                class="bg-red-100 text-red-700 border border-red-400 px-4 py-2 rounded mt-4 hover:bg-red-200">
                                            {{ __('Delete Section') }}
                                        </button>

                                    </div>
                                    @foreach ($workExperiences as $index => $item)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-2">
                                            <x-forms.input id="company" name="company" label="Company"
                                                           wireModel="workExperiences.{{ $index }}.company"/>
                                            <x-forms.input id="workstation" name="workstation"
                                                           label="Workstation"
                                                           wireModel="workExperiences.{{ $index }}.position"/>

                                            <x-forms.date-picker id="start-{{ $index }}-experience"
                                                                 name="start-{{ $index }}-experience"
                                                                 label="Start Day"
                                                                 wireModel="workExperiences.{{ $index }}.start"/>
                                            <x-forms.date-picker id="end-{{ $index }}-experience"
                                                                 name="end-{{ $index }}-experience"
                                                                 label="End Day"
                                                                 wireModel="workExperiences.{{ $index }}.end"/>

                                            <textarea wire:model="workExperiences.{{ $index }}.description" placeholder="{{ __('Description') }}"
                                                      class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm md:col-span-2"></textarea>
                                        </div>
                                        <button wire:click="removeEntry('work_experience', {{ $index }})"
                                                class="bg-red-500 text-white px-3 py-1 rounded mt-5 mb-5">{{ __('Delete Experience') }}
                                        </button>
                                    @endforeach
                                    <button wire:click="addEntry('work_experience')"
                                            class="bg-blue-500 text-white px-3 py-1 rounded">
                                        + {{ __('Add Experience') }}
                                    </button>
                                </div>
                            @endif

                            {{-- Educación --}}
                            @if (in_array('education', $activeSections))
                                <div class="border border-gray-200 shadow-md p-6 rounded-2xl bg-gray-100">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="font-bold text-xl flex items-center gap-2">
                                            {{ __('Education') }}
                                        </h3>
                                        <button wire:click="removeSection('education')"
                                                class="bg-red-100 text-red-700 border border-red-400 px-4 py-2 rounded mt-4 hover:bg-red-200">
                                            {{ __('Delete Section') }}
                                        </button>

                                    </div>
                                    @foreach ($educations as $index => $item)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-2">
                                            <x-forms.input id="educational_center" name="educational_center"
                                                           label="Educational Center"
                                                           wireModel="educations.{{ $index }}.school"/>
                                            <x-forms.input id="course" name="course"
                                                           label="Course"
                                                           wireModel="educations.{{ $index }}.degree"/>
                                            <x-forms.date-picker id="start-{{ $index }}-education"
                                                                 name="start-{{ $index }}-education"
                                                                 label="Start Day"
                                                                 wireModel="educations.{{ $index }}.start"/>
                                            <x-forms.date-picker id="end-{{ $index }}-education"
                                                                 name="end-{{ $index }}-education"
                                                                 label="End Day"
                                                                 wireModel="educations.{{ $index }}.end"/>
                                            <x-forms.input id="educational_center_city" name="educational_center_city"
                                                           label="City"
                                                           wireModel="educations.{{ $index }}.city"/>
                                            <x-forms.input id="educational_center_country" name="educational_center_country"
                                                           label="Country"
                                                           wireModel="educations.{{ $index }}.country"/>
                                        </div>
                                        <button wire:click="removeEntry('education', {{ $index }})"
                                                class="bg-red-500 text-white px-3 py-1 rounded mt-5 mb-5">{{ __('Delete Education') }}
                                        </button>
                                    @endforeach
                                    <button wire:click="addEntry('education')"
                                            class="bg-blue-500 text-white px-3 py-1 rounded">
                                        + {{ __('Add Education') }}
                                    </button>
                                </div>
                            @endif

                            {{-- Idiomas --}}
                            @if (in_array('languages', $activeSections))
                                <div class="border border-gray-200 shadow-md p-6 rounded-2xl bg-gray-100">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="font-bold text-xl flex items-center gap-2">
                                            {{ __('Languages') }}
                                        </h3>
                                        <button wire:click="removeSection('languages')"
                                                class="bg-red-100 text-red-700 border border-red-400 px-4 py-2 rounded mt-4 hover:bg-red-200">
                                            {{ __('Delete Section') }}
                                        </button>
                                    </div>
                                    @foreach ($languages as $index => $item)
                                        <div class="flex items-center space-x-2 mb-2">
                                            <x-forms.select-input
                                                id="language"
                                                name="name"
                                                label="Language"
                                                :options="$languages_options"
                                                selectedValue="languages.{{ $index }}.language_id"
                                            />

                                            <x-forms.input id="language_level" name="language_level"
                                                           label="Level"
                                                           wireModel="languages.{{ $index }}.level"/>
                                        </div>
                                        <button wire:click="removeEntry('languages', {{ $index }})"
                                                class="bg-red-500 text-white px-3 py-1 rounded mt-5 mb-5">
                                            {{ __('Delete Language') }}
                                        </button>
                                    @endforeach
                                    <button wire:click="addEntry('languages')"
                                            class="bg-blue-500 text-white px-3 py-1 rounded">
                                        + {{ __('Add Language') }}
                                    </button>
                                </div>
                            @endif

                            {{-- Habilidades --}}
                            @if (in_array('skills', $activeSections))
                                <div class="border border-gray-200 shadow-md p-6 rounded-2xl bg-gray-100">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="font-bold text-xl flex items-center gap-2">
                                            {{ __('Skills') }}
                                        </h3>
                                        <button wire:click="removeSection('skills')"
                                                class="bg-red-100 text-red-700 border border-red-400 px-4 py-2 rounded mt-4 hover:bg-red-200">
                                            {{ __('Delete Section') }}
                                        </button>
                                    </div>
                                    @foreach ($skills as $index => $item)
                                        <div class="flex items-center space-x-2 mb-2">
                                            <x-forms.select-input
                                                id="skill"
                                                name="name"
                                                label="skill"
                                                :options="$skills_options"
                                                selectedValue="skills.{{ $index }}.digital_skill_id"
                                            />
                                            <x-forms.input id="skill_level" name="skill_level"
                                                           label="Level"
                                                           wireModel="skills.{{ $index }}.level"/>
                                        </div>
                                        <button wire:click="removeEntry('skills', {{ $index }})"
                                                class="bg-red-500 text-white px-3 py-1 rounded mt-5 mb-5">
                                            {{ __('Delete Skill') }}
                                        </button>
                                    @endforeach
                                    <button wire:click="addEntry('skills')"
                                            class="bg-blue-500 text-white px-3 py-1 rounded">
                                        + {{ __('Add Skill') }}
                                    </button>
                                </div>
                            @endif

                            @if (in_array('driving_licenses', $activeSections))
                                <div class="border border-gray-200 shadow-md p-6 rounded-2xl bg-gray-100">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="font-bold text-xl flex items-center gap-2">
                                            {{ __('Driving Licenses') }}
                                        </h3>
                                        <button wire:click="removeSection('driving_licenses')"
                                                class="bg-red-100 text-red-700 border border-red-400 px-4 py-2 rounded mt-4 hover:bg-red-200">
                                            {{ __('Delete Section') }}
                                        </button>
                                    </div>
                                    @foreach ($drivingLicenses as $index => $item)
                                        <div class="flex items-center space-x-2 mb-2">
                                            <x-forms.select-input
                                                id="driving_license"
                                                name="category"
                                                label="Driving License"
                                                :options="$drivingLicenses_options"
                                                selectedValue="drivingLicenses.{{ $index }}.driving_license_id"
                                            />
                                        </div>
                                        <button wire:click="removeEntry('driving_licenses', {{ $index }})"
                                                class="bg-red-500 text-white px-3 py-1 rounded mt-5 mb-5">
                                            {{ __('Delete Driving License') }}
                                        </button>
                                    @endforeach
                                    <button wire:click="addEntry('driving_licenses')"
                                            class="bg-blue-500 text-white px-3 py-1 rounded">
                                        + {{ __('Add Driving License') }}
                                    </button>
                                </div>
                            @endif

                            {{-- Selector de secciones --}}
                            <div>
                                <label class="font-semibold">{{ __('Add Section') }}:</label>
                                <select wire:change="addSection($event.target.value)"
                                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm w-1/3">
                                    <option value="">-- {{ __('Choose') }} --</option>
                                    @foreach ($availableSections as $key => $label)
                                        @if (!in_array($key, $activeSections))
                                            <option value="{{ $key }}">{{ $label }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <x-primary-button wire:click="update">{{ __('Update CV') }}</x-primary-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
