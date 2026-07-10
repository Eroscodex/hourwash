<x-app-layout>

    <x-slot name="header">

        <div class="flex flex-col sm:flex-row sm:items-center w-full gap-4">

            <h2 class="font-semibold text-2xl text-gray-800">
                Manage Machines
            </h2>


            <a href="{{ route('admin.machines.create') }}"
            class="sm:ml-auto bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg w-fit">

                + Add Machine

            </a>

        </div>

    </x-slot>

    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            @if(session('success'))

                <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg mb-5">
                    {{ session('success') }}
                </div>

            @endif



            <div class="bg-white shadow rounded-xl overflow-hidden">

            <div class="overflow-x-auto">


                <table class="w-full text-left text-sm sm:text-base">


                    <thead class="bg-blue-600 text-white text-sm">

                        <tr>

                            <th class="px-6 py-4">
                                Machine Name
                            </th>


                            <th class="px-6 py-4">
                                Code
                            </th>


                            <th class="px-6 py-4">
                                Status
                            </th>


                            <th class="px-6 py-4 text-center">
                                Actions
                            </th>

                        </tr>

                    </thead>



                    <tbody class="divide-y">


                    @forelse($machines as $machine)


                        <tr class="hover:bg-gray-50">


                            <td class="px-6 py-4">
                                {{ $machine->machine_name }}
                            </td>


                            <td class="px-6 py-4">
                                {{ $machine->machine_code }}
                            </td>



                            <td class="px-6 py-4">


                                @if($machine->status == 'Available')

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                        Available
                                    </span>

                                @elseif($machine->status == 'In Use')

                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">
                                        In Use
                                    </span>

                                @else

                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                        Maintenance
                                    </span>

                                @endif


                            </td>



                            <td class="px-6 py-4 text-center">


                                <a href="{{ route('admin.machines.edit',$machine) }}"
                                class="text-blue-600 mr-3 text-sm sm:text-base">
                                    ✏ Edit
                                </a>



                                <form action="{{ route('admin.machines.destroy',$machine) }}"
                                      method="POST"
                                      class="inline">

                                    @csrf
                                    @method('DELETE')


                                    <button class="text-red-600 text-sm sm:text-base">

                                        🗑 Delete

                                    </button>


                                </form>


                            </td>


                        </tr>


                    @empty


                        <tr>

                            <td colspan="4"
                                class="text-center py-6 text-gray-500">

                                No machines found.

                            </td>

                        </tr>


                    @endforelse


                    </tbody>


                </table>


            </div>



            <div class="mt-5">

                {{ $machines->links() }}

            </div>


        </div>

    </div>


</x-app-layout>