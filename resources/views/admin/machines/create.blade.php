<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800">
            Add Machine
        </h2>
    </x-slot>


    <div class="py-8">

        <div class="max-w-xl mx-auto bg-white shadow rounded-xl p-6">


            <form method="POST" action="{{ route('admin.machines.store') }}">

                @csrf


                <div class="mb-4">

                    <label class="block text-gray-700 mb-2">
                        Machine Name
                    </label>

                    <input type="text"
                           name="machine_name"
                           class="w-full border rounded-lg p-2"
                           placeholder="Example: Washing Machine 1">

                </div>



                <div class="mb-4">

                    <label class="block text-gray-700 mb-2">
                        Machine Code
                    </label>

                    <input type="text"
                           name="machine_code"
                           class="w-full border rounded-lg p-2"
                           placeholder="Example: WM-001">

                </div>



                <div class="mb-4">

                    <label class="block text-gray-700 mb-2">
                        Status
                    </label>


                    <select name="status"
                            class="w-full border rounded-lg p-2">

                        <option value="Available">
                            Available
                        </option>

                        <option value="In Use">
                            In Use
                        </option>

                        <option value="Maintenance">
                            Maintenance
                        </option>

                    </select>

                </div>



                <div class="flex gap-3">


                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg">

                        Save Machine

                    </button>


                    <a href="{{ route('admin.machines.index') }}"
                       class="bg-gray-300 px-5 py-2 rounded-lg">

                        Cancel

                    </a>


                </div>


            </form>


        </div>

    </div>


</x-app-layout>