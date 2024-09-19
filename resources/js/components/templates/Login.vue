<template>
    <div class="w-screen h-screen flex items-center">
        <div class="container">
            <div>
                <div class="text-center mb-4">
                    <h4 class="text-3xl text-blue-500 font-semibold mb-4">
                        bisaKok!
                    </h4>

                    <h6 class="text-xl font-semibold text-gray-600">
                        Sign in to your account
                    </h6>
                </div>

                <div class="lg:max-w-[50%] md:max-w-[65%] mx-auto">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
