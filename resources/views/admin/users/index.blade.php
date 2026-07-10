<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-gray-800">
                Manage Users
            </h2>
        </div>
    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-5 bg-green-100 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif


            <div class="bg-white rounded-xl shadow overflow-hidden">

                <table class="w-full text-left">

                    <thead class="bg-blue-600 text-white">

                        <tr>
                            <th class="px-6 py-4">
                                Name
                            </th>

                            <th class="px-6 py-4">
                                Email
                            </th>

                            <th class="px-6 py-4">
                                Role
                            </th>

                            <th class="px-6 py-4 text-center">
                                Actions
                            </th>
                        </tr>

                    </thead>


                    <tbody class="divide-y">


                    @forelse($users as $user)

                        <tr class="hover:bg-gray-50">


                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $user->name }}
                            </td>


                            <td class="px-6 py-4 text-gray-600">
                                {{ $user->email }}
                            </td>


                            <td class="px-6 py-4">

                                @if($user->role === 'admin')

                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm">
                                        Admin
                                    </span>

                                @else

                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm">
                                        User
                                    </span>

                                @endif

                            </td>


                            <td class="px-6 py-4 text-center">



                                <form action="{{ route('admin.users.destroy',$user) }}"
                                      method="POST"
                                      class="inline">

                                    @csrf
                                    @method('DELETE')


                                    <button
                                        onclick="return confirm('Delete this user?')"
                                        class="text-red-600 hover:text-red-800">

                                        🗑 Delete

                                    </button>


                                </form>


                            </td>


                        </tr>


                    @empty

                        <tr>
                            <td colspan="4"
                                class="px-6 py-6 text-center text-gray-500">

                                No users found.

                            </td>
                        </tr>

                    @endforelse


                    </tbody>


                </table>

            </div>


            <div class="mt-5">
                {{ $users->links() }}
            </div>


        </div>

    </div>


</x-app-layout>