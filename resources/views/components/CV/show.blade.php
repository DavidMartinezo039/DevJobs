<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg relative">
            <div class="absolute top-6 left-6">
                <img class="w-32 h-32 object-cover rounded-full border-4 border-white shadow-lg"
                     src="{{ asset('storage/images/' . $personalData->image) }}" alt="Profile Image">
            </div>
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <h1 class="text-4xl font-bold text-center my-10">
                    {{ $selectedCv->title }}
                </h1>

                <h2 class="text-2xl font-bold text-center my-10">{{ __('Personal Data') }}</h2>
                <div class="md:flex md:justify-center p-5">
                    <div class="md:w-1/2 space-y-5">
                        <x-profile-field label="First Name" :value="$personalData->first_name"/>

                        <x-profile-field label="Last Name" :value="$personalData->last_name"/>

                        <x-profile-field label="About Me" :value="$personalData->about_me"/>

                        <x-profile-field
                            label="Birth Date"
                            :value="$personalData->birth_date"
                            :useFormattedDate="true"/>

                        <x-profile-field label="City" :value="$personalData->city"/>

                        <x-profile-field label="Country" :value="$personalData->country"/>

                        <x-profile-field-bucle label="Nationalities" :values="$personalData->nationalities"/>

                        <x-profile-field-bucle label="Work Permits" :values="$personalData->workPermits"/>

                        <x-profile-field-bucle label="Emails" :values="$personalData->emails"/>

                        <x-profile-field-bucle label="Addresses" :values="$personalData->addresses"/>

                        <x-profile-field label="Gender" :value="$personalData->gender->type ?? ''"/>

                        <x-profile-field-bucle label="Identity Documents" :values="$personalData->identities"
                                               :items="['type']" :data-pivot="['number']"/>

                        <x-profile-field-bucle label="Phone Numbers" :values="$personalData->phones" :items="['type']"
                                               :data-pivot="['number']"/>

                        <x-profile-field-bucle label="Social Media" :values="$personalData->socialMedia"
                                               :items="['type']" :data-pivot="['user_name', 'url']"/>

                        <x-profile-field-bucle label="Work Experiences" :values="$selectedCv->workExperiences"
                                               :items="['company_name', 'position', 'start_date', 'end_date', 'description']"/>

                        <x-profile-field-bucle label="Education" :values="$selectedCv->education"
                                               :items="['institution', 'city', 'country', 'title', 'start_date', 'end_date']"/>

                        <x-profile-field-bucle label="Driving Licenses" :values="$selectedCv->drivingLicenses"
                                               :items="['category', 'vehicle_type']"/>

                        <x-profile-field-bucle label="Languages" :values="$selectedCv->languages"
                                               :items="['name']" :data-pivot="['level']"/>

                        <x-profile-field-bucle label="Skills" :values="$selectedCv->digitalSkills"
                                               :items="['name']" :data-pivot="['level']"/>

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
                                                       label="{{ __('Level') }}"
                                                       wireModel="skills.{{ $index }}.level"/>
                                    </div>
                                    <button wire:click="removeEntry('skills', {{ $index }})"
                                            class="bg-red-500 text-white px-3 py-1 rounded mt-5 mb-5">
                                        {{ __('Delete Skill') }}
                                    </button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
