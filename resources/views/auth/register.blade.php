<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        {{-- PERSONAL INFO SECTION --}}
        <h2 class="text-lg font-semibold mb-4">Personal Information</h2>

        <div class="space-y-4">
            <!-- Full Name -->
            <div>
                <x-input-label for="name" :value="__('Full Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Phone -->
             <!--
            <div>
                <x-input-label for="phone" :value="__('Phone Number')" />
                <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div> -->

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- IC -->
             <!--
            <div>
                <x-input-label for="ic_number" :value="__('IC Number')" />
                <x-text-input id="ic_number" class="block mt-1 w-full" type="text" name="ic_number" :value="old('ic_number')" required />
                <x-input-error :messages="$errors->get('ic_number')" class="mt-2" />
            </div>  -->

            <!-- Faculty 
            <div>
                <x-input-label for="faculty" :value="__('Faculty')" />
                <select id="faculty" name="faculty" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    <option value="">-- Select Faculty --</option>
                    <option value="computing">Faculty of Computing</option>
                    <option value="engineering">Faculty of Engineering</option>
                    <option value="business">Faculty of Business</option>
                </select>
                <x-input-error :messages="$errors->get('faculty')" class="mt-2" />
            </div>-->

            <!-- College 
            <div>
                <x-input-label for="college" :value="__('College')" />
                <select id="college" name="college" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    <option value="">-- Select College --</option>
                    <option value="kug">Kulliyyah of Science</option>
                    <option value="kit">Kulliyyah of IT</option>
                    <option value="kbs">Kulliyyah of Business</option>
                </select>
                <x-input-error :messages="$errors->get('college')" class="mt-2" />
            </div> -->

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <!--
        {{-- BANKING INFO SECTION --}}
        <h2 class="text-lg font-semibold mt-8 mb-4">Banking Information</h2>

        <div class="space-y-4">
            <div>
                <x-input-label for="bank_name" :value="__('Bank Name')" />
                <x-text-input id="bank_name" class="block mt-1 w-full" type="text" name="bank_name" :value="old('bank_name')" required />
                <x-input-error :messages="$errors->get('bank_name')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="account_no" :value="__('Account Number')" />
                <x-text-input id="account_no" class="block mt-1 w-full" type="text" name="account_no" :value="old('account_no')" required />
                <x-input-error :messages="$errors->get('account_no')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="bank_type" :value="__('Bank Type')" />
                <select id="bank_type" name="bank_type" class="block mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                    <option value="">-- Select Bank --</option>
                    <option value="maybank">Maybank</option>
                    <option value="cimb">CIMB</option>
                    <option value="rhb">RHB</option>
                    <option value="public">Public Bank</option>
                </select>
                <x-input-error :messages="$errors->get('bank_type')" class="mt-2" />
            </div>
        </div>

        {{-- UPLOAD DOCUMENTS SECTION --}}
        <h2 class="text-lg font-semibold mt-8 mb-4">Upload Documents</h2>

        <div class="space-y-4">
            <div>
                <x-input-label for="ic_file" :value="__('Upload IC')" />
                <input id="ic_file" type="file" name="ic_file" class="block mt-1 w-full" required>
                <x-input-error :messages="$errors->get('ic_file')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="driver_license" :value="__('Upload Driver License')" />
                <input id="driver_license" type="file" name="driver_license" class="block mt-1 w-full" required>
                <x-input-error :messages="$errors->get('driver_license')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="matric_card" :value="__('Upload Matric Card')" />
                <input id="matric_card" type="file" name="matric_card" class="block mt-1 w-full" required>
                <x-input-error :messages="$errors->get('matric_card')" class="mt-2" />
            </div>
        </div> -->

        {{-- SUBMIT --}}
        <div class="flex items-center justify-end mt-8">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
