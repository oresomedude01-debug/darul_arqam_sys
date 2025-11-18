@extends('layouts.modern')

@section('title', 'Token Details')

@section('breadcrumb')
    <span class="text-gray-400">Student Management</span>
    <span class="text-gray-400">/</span>
    <a href="{{ route('tokens.index') }}" class="text-gray-400 hover:text-gray-600">Registration Tokens</a>
    <span class="text-gray-400">/</span>
    <span class="font-semibold text-gray-900">Token Details</span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Token Details</h1>
            <p class="text-gray-600 mt-1">View registration token information and usage</p>
        </div>
        <a href="{{ route('tokens.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to List
        </a>
    </div>

    <!-- Token Code Card -->
    <div class="card">
        <div class="card-body">
            <div class="text-center py-8">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-primary-100 mb-4">
                    <i class="fas fa-ticket-alt text-primary-600 text-3xl"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $token->token_code }}</h2>
                <div class="flex items-center justify-center space-x-3">
                    @if($token->status === 'active')
                        <span class="badge badge-success text-lg px-4 py-2">
                            <i class="fas fa-check-circle mr-2"></i> Active
                        </span>
                    @elseif($token->status === 'consumed')
                        <span class="badge badge-info text-lg px-4 py-2">
                            <i class="fas fa-user-check mr-2"></i> Consumed
                        </span>
                    @elseif($token->status === 'expired')
                        <span class="badge badge-warning text-lg px-4 py-2">
                            <i class="fas fa-clock mr-2"></i> Expired
                        </span>
                    @else
                        <span class="badge badge-danger text-lg px-4 py-2">
                            <i class="fas fa-ban mr-2"></i> Disabled
                        </span>
                    @endif
                </div>

                <div class="mt-6">
                    <button onclick="copyToClipboard('{{ $token->token_code }}')" class="btn btn-primary">
                        <i class="fas fa-copy mr-2"></i>
                        Copy Token Code
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Token Information -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Basic Info -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Session/Academic Year</p>
                    <p class="text-gray-900 font-medium mt-1">
                        {{ $token->session_year ?? 'Not specified' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-600">Class Level</p>
                    <p class="text-gray-900 font-medium mt-1">
                        @if($token->class_level)
                            <span class="badge badge-primary">{{ $token->class_level }}</span>
                        @else
                            <span class="text-gray-400">Any class</span>
                        @endif
                    </p>
                </div>

                @if($token->note)
                <div>
                    <p class="text-sm text-gray-600">Note/Description</p>
                    <p class="text-gray-900 mt-1">{{ $token->note }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Dates & Timeline -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-semibold text-gray-900">Timeline</h3>
            </div>
            <div class="card-body space-y-4">
                <div>
                    <p class="text-sm text-gray-600">Created On</p>
                    <p class="text-gray-900 font-medium mt-1">
                        {{ $token->created_at->format('F d, Y \a\t h:i A') }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $token->created_at->diffForHumans() }}
                    </p>
                </div>

                @if($token->expires_at)
                <div>
                    <p class="text-sm text-gray-600">Expires On</p>
                    <p class="font-medium mt-1 {{ $token->expires_at->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                        {{ $token->expires_at->format('F d, Y \a\t h:i A') }}
                    </p>
                    <p class="text-xs {{ $token->expires_at->isPast() ? 'text-red-500' : 'text-gray-500' }} mt-1">
                        @if($token->expires_at->isPast())
                            Expired {{ $token->expires_at->diffForHumans() }}
                        @else
                            Expires {{ $token->expires_at->diffForHumans() }}
                        @endif
                    </p>
                </div>
                @else
                <div>
                    <p class="text-sm text-gray-600">Expiry</p>
                    <p class="text-gray-900 font-medium mt-1">
                        <span class="badge badge-success">No expiry</span>
                    </p>
                </div>
                @endif

                @if($token->consumed_at)
                <div>
                    <p class="text-sm text-gray-600">Consumed On</p>
                    <p class="text-gray-900 font-medium mt-1">
                        {{ $token->consumed_at->format('F d, Y \a\t h:i A') }}
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        {{ $token->consumed_at->diffForHumans() }}
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Usage Information -->
    @if($token->student)
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">
                <i class="fas fa-user-graduate mr-2 text-primary-600"></i>
                Student Enrolled with This Token
            </h3>
        </div>
        <div class="card-body">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-16 h-16 rounded-full bg-primary-100 flex items-center justify-center">
                        @if($token->student->photo_path)
                            <img src="{{ asset($token->student->photo_path) }}"
                                 alt="{{ $token->student->full_name }}"
                                 class="w-full h-full rounded-full object-cover">
                        @else
                            <span class="text-2xl font-bold text-primary-600">
                                {{ substr($token->student->first_name, 0, 1) }}{{ substr($token->student->last_name, 0, 1) }}
                            </span>
                        @endif
                    </div>
                    <div>
                        <p class="text-lg font-semibold text-gray-900">{{ $token->student->full_name }}</p>
                        <p class="text-sm text-gray-600">{{ $token->student->admission_number }}</p>
                        <div class="flex items-center space-x-2 mt-1">
                            <span class="badge badge-primary">{{ $token->student->class_level }}</span>
                            <span class="badge badge-{{ $token->student->status === 'active' ? 'success' : 'warning' }}">
                                {{ ucfirst($token->student->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('students.show', $token->student->id) }}" class="btn btn-primary">
                    <i class="fas fa-arrow-right mr-2"></i>
                    View Student Profile
                </a>
            </div>

            @if($token->consumed_by_ip)
            <div class="mt-4 pt-4 border-t border-gray-200">
                <p class="text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Enrolled from IP: <code class="bg-gray-100 px-2 py-1 rounded">{{ $token->consumed_by_ip }}</code>
                </p>
            </div>
            @endif
        </div>
    </div>
    @else
    <div class="card">
        <div class="card-body text-center py-8">
            <i class="fas fa-hourglass-half text-gray-400 text-4xl mb-3"></i>
            <p class="text-gray-600">This token has not been used yet</p>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
        </div>
        <div class="card-body">
            <div class="flex flex-wrap gap-3">
                @if($token->status === 'active' && !$token->student)
                    <form method="POST" action="{{ route('tokens.update', $token->id) }}" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="disabled">
                        <button type="submit"
                                class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to disable this token?')">
                            <i class="fas fa-ban mr-2"></i>
                            Disable Token
                        </button>
                    </form>
                @elseif($token->status === 'disabled')
                    <form method="POST" action="{{ route('tokens.update', $token->id) }}" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="active">
                        <button type="submit"
                                class="btn btn-success"
                                onclick="return confirm('Are you sure you want to enable this token?')">
                            <i class="fas fa-check-circle mr-2"></i>
                            Enable Token
                        </button>
                    </form>
                @endif

                <button onclick="window.print()" class="btn btn-outline">
                    <i class="fas fa-print mr-2"></i>
                    Print
                </button>

                <button onclick="copyToClipboard('{{ $token->token_code }}')" class="btn btn-outline">
                    <i class="fas fa-copy mr-2"></i>
                    Copy Token
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(() => {
        Toast.success('Token code copied to clipboard!');
    });
}
</script>
@endpush
@endsection
