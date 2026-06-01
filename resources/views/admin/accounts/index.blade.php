@extends('layouts.admin')

@section('title', 'Account Management')

@section('content')
<div class="w-full px-margin-mobile md:px-margin-desktop">
    <div class="max-w-container-max mx-auto space-y-stack-xl">
<!-- Page Header -->
<div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
    <div>
        <h2 class="font-h2 text-h2 text-on-surface mb-1">Account Management</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">Manage users, roles, and platform access.</p>
    </div>
    <div class="flex items-center gap-3">
        <button class="flex items-center gap-2 px-4 py-2 rounded-lg border border-outline-variant text-on-surface-variant font-label-sm text-label-sm hover:bg-surface-container transition-colors bg-surface-container-lowest">
            <span class="material-symbols-outlined" data-icon="filter_list">filter_list</span>
            Filters
        </button>
        <button class="flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary-container text-on-primary font-label-sm text-label-sm hover:bg-primary transition-colors shadow-sm">
            <span class="material-symbols-outlined" data-icon="add">add</span>
            Add New User
        </button>
    </div>
</div>

<!-- Bento Grid / Glassmorphism approach for Table Area -->
<div class="bg-surface-container-lowest rounded-xl border border-surface-variant ambient-shadow overflow-hidden">
    <!-- Toolbar -->
    <div class="p-4 border-b border-surface-variant flex flex-col sm:flex-row gap-4 items-center justify-between bg-surface-bright">
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <div class="relative w-full sm:w-64">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                <input class="w-full pl-9 pr-3 py-1.5 rounded-md bg-surface-container border border-outline-variant focus:border-primary-container text-body-md font-body-md outline-none text-sm transition-all" placeholder="Search by name or email..." type="text"/>
            </div>
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto overflow-x-auto pb-1 sm:pb-0">
            <span class="font-label-sm text-label-sm text-on-surface-variant whitespace-nowrap">Role:</span>
            <select class="pl-3 pr-8 py-1.5 rounded-md bg-surface-container border border-outline-variant text-body-md font-body-md text-sm outline-none focus:border-primary-container">
                <option>All Roles</option>
                <option>Retailer</option>
                <option>Distributor</option>
                <option>Farmer</option>
            </select>
            <span class="font-label-sm text-label-sm text-on-surface-variant whitespace-nowrap ml-2">Status:</span>
            <select class="pl-3 pr-8 py-1.5 rounded-md bg-surface-container border border-outline-variant text-body-md font-body-md text-sm outline-none focus:border-primary-container">
                <option>All Status</option>
                <option>Active</option>
                <option>Inactive</option>
            </select>
        </div>
    </div>
    
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low border-b border-surface-variant">
                    <th class="py-3 px-6 font-label-sm text-label-sm text-on-surface-variant font-semibold">User</th>
                    <th class="py-3 px-6 font-label-sm text-label-sm text-on-surface-variant font-semibold">Role</th>
                    <th class="py-3 px-6 font-label-sm text-label-sm text-on-surface-variant font-semibold">Status</th>
                    <th class="py-3 px-6 font-label-sm text-label-sm text-on-surface-variant font-semibold">Registration Date</th>
                    <th class="py-3 px-6 font-label-sm text-label-sm text-on-surface-variant font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-variant bg-surface-container-lowest">
                @forelse ($users as $user)
                <tr class="hover:bg-surface-container/50 transition-colors group">
                    <td class="py-4 px-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary-container/10 flex items-center justify-center text-primary-container font-bold text-sm uppercase">
                                {{ substr($user->name, 0, 2) }}
                            </div>
                            <div>
                                <p class="font-body-md text-body-md text-on-surface font-medium">{{ $user->name }}</p>
                                <p class="font-caption text-caption text-on-surface-variant">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-secondary-container text-on-secondary-container text-xs font-medium capitalize">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="py-4 px-6">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-primary-container/10 text-primary-container font-caption text-caption">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary-container"></span>
                            Active
                        </span>
                    </td>
                    <td class="py-4 px-6 font-body-md text-body-md text-on-surface-variant text-sm">
                        {{ $user->created_at->format('M d, Y') }}
                    </td>
                    <td class="py-4 px-6 text-right">
                        <button class="p-1.5 text-on-surface-variant hover:text-primary-container rounded opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-sm" data-icon="edit">edit</span>
                        </button>
                        <button class="p-1.5 text-on-surface-variant hover:text-error rounded opacity-0 group-hover:opacity-100 transition-opacity">
                            <span class="material-symbols-outlined text-sm" data-icon="delete">delete</span>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-8 text-center text-on-surface-variant">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <div class="p-4 border-t border-surface-variant bg-surface-bright">
        {{ $users->links() }}
    </div>
    </div>
</div>
@endsection
