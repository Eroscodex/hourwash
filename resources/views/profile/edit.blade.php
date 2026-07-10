<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <h2 class="font-bold text-2xl text-gray-800">
                👤 My Profile
            </h2>

        </div>

    </x-slot>


    <div class="py-10 bg-gray-100">

        <div class="max-w-5xl mx-auto px-4 space-y-6">


            <!-- Profile Information -->

            <div class="bg-white shadow-xl rounded-2xl overflow-hidden">


                <div class="bg-gradient-to-r from-blue-700 to-cyan-500 p-6 text-white">

                    <h3 class="text-xl font-bold">
                        Account Information
                    </h3>

                    <p class="text-blue-100 text-sm">
                        Update your name and email address
                    </p>

                </div>


                <div class="p-6">

                    <div class="max-w-xl">

                        @include('profile.partials.update-profile-information-form')

                    </div>

                </div>


            </div>



            <!-- Password Update -->


            <div class="bg-white shadow-xl rounded-2xl overflow-hidden">


                <div class="bg-gradient-to-r from-cyan-500 to-blue-600 p-6 text-white">

                    <h3 class="text-xl font-bold">
                        🔒 Change Password
                    </h3>

                    <p class="text-blue-100 text-sm">
                        Keep your account secure
                    </p>

                </div>


                <div class="p-6">

                    <div class="max-w-xl">

                        @include('profile.partials.update-password-form')

                    </div>

                </div>


            </div>




            <!-- Delete Account -->


            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-red-200">


                <div class="bg-red-600 p-6 text-white">

                    <h3 class="text-xl font-bold">
                        ⚠ Delete Account
                    </h3>


                    <p class="text-red-100 text-sm">
                        Permanently remove your account
                    </p>

                </div>



                <div class="p-6">

                    <div class="max-w-xl">

                        @include('profile.partials.delete-user-form')

                    </div>


                </div>


            </div>



        </div>

    </div>


</x-app-layout>