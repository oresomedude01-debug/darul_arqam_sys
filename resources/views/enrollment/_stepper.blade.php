{{-- Multi-step Progress Stepper Component --}}
<div class="mb-8">
    <!-- Step Indicator - Desktop View -->
    <div class="hidden md:block">
        <div class="step-wizard">
            <!-- Step 1 -->
            <div class="step-item {{ $currentStep >= 1 ? 'active' : '' }} {{ $currentStep > 1 ? 'completed' : '' }}">
                <div class="step-content">
                    <div class="step-circle">
                        @if($currentStep > 1)
                            <i class="fas fa-check text-sm"></i>
                        @else
                            1
                        @endif
                    </div>
                    <div class="step-line"></div>
                </div>
                <div class="step-label">
                    <p class="font-semibold text-sm">Student Info</p>
                    <p class="text-xs text-gray-500">Basic details</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="step-item {{ $currentStep >= 2 ? 'active' : '' }} {{ $currentStep > 2 ? 'completed' : '' }}">
                <div class="step-content">
                    <div class="step-circle">
                        @if($currentStep > 2)
                            <i class="fas fa-check text-sm"></i>
                        @else
                            2
                        @endif
                    </div>
                    <div class="step-line"></div>
                </div>
                <div class="step-label">
                    <p class="font-semibold text-sm">Previous School</p>
                    <p class="text-xs text-gray-500">Academic history</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="step-item {{ $currentStep >= 3 ? 'active' : '' }} {{ $currentStep > 3 ? 'completed' : '' }}">
                <div class="step-content">
                    <div class="step-circle">
                        @if($currentStep > 3)
                            <i class="fas fa-check text-sm"></i>
                        @else
                            3
                        @endif
                    </div>
                    <div class="step-line"></div>
                </div>
                <div class="step-label">
                    <p class="font-semibold text-sm">Health Info</p>
                    <p class="text-xs text-gray-500">Medical details</p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="step-item {{ $currentStep >= 4 ? 'active' : '' }} {{ $currentStep > 4 ? 'completed' : '' }}">
                <div class="step-content">
                    <div class="step-circle">
                        @if($currentStep > 4)
                            <i class="fas fa-check text-sm"></i>
                        @else
                            4
                        @endif
                    </div>
                    <div class="step-line"></div>
                </div>
                <div class="step-label">
                    <p class="font-semibold text-sm">Guardian Info</p>
                    <p class="text-xs text-gray-500">Contact details</p>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="step-item {{ $currentStep >= 5 ? 'active' : '' }} {{ $currentStep > 5 ? 'completed' : '' }}">
                <div class="step-content">
                    <div class="step-circle">
                        @if($currentStep > 5)
                            <i class="fas fa-check text-sm"></i>
                        @else
                            5
                        @endif
                    </div>
                </div>
                <div class="step-label">
                    <p class="font-semibold text-sm">Review</p>
                    <p class="text-xs text-gray-500">Confirm & submit</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Progress Bar -->
    <div class="md:hidden">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-semibold text-gray-700">Step {{ $currentStep }} of 5</span>
            <span class="text-sm text-gray-600">{{ $currentStep * 20 }}% Complete</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="bg-primary-600 h-3 rounded-full transition-all duration-300"
                 style="width: {{ $currentStep * 20 }}%"></div>
        </div>
        <div class="mt-3 text-center">
            <p class="text-sm font-medium text-gray-900">{{ $stepTitle }}</p>
            <p class="text-xs text-gray-600">{{ $stepDescription }}</p>
        </div>
    </div>
</div>

<style>
    /* Step Wizard Styling */
    .step-wizard {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        position: relative;
        padding: 0;
        margin: 0 0 2rem 0;
    }

    .step-item {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
    }

    .step-content {
        display: flex;
        align-items: center;
        width: 100%;
        margin-bottom: 0.5rem;
    }

    .step-circle {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 50%;
        border: 2px solid var(--color-gray-300);
        background: white;
        display: flex;
        align-items: center;
        justify-center;
        font-weight: 600;
        color: var(--color-gray-500);
        position: relative;
        z-index: 2;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .step-line {
        flex: 1;
        height: 2px;
        background: var(--color-gray-300);
        margin-left: 0.5rem;
        position: relative;
        transition: all 0.3s ease;
    }

    .step-item:last-child .step-line {
        display: none;
    }

    .step-label {
        text-align: center;
        margin-top: 0.5rem;
    }

    /* Active Step */
    .step-item.active .step-circle {
        border-color: var(--color-primary-600);
        background: var(--color-primary-600);
        color: white;
        box-shadow: 0 0 0 4px var(--color-primary-100);
    }

    /* Completed Step */
    .step-item.completed .step-circle {
        border-color: var(--color-green-500);
        background: var(--color-green-500);
        color: white;
    }

    .step-item.completed .step-line {
        background: var(--color-green-500);
    }

    /* Label color for active/completed */
    .step-item.active .step-label p:first-child {
        color: var(--color-primary-600);
    }

    .step-item.completed .step-label p:first-child {
        color: var(--color-green-600);
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .step-label p {
            font-size: 0.75rem;
        }
        .step-label p.text-xs {
            display: none;
        }
    }
</style>
