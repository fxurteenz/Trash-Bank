<div x-data="wasteBankApp()" x-init=" $nextTick(() => { lucide.createIcons() })" class="min-h-screen">

    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center gap-3">
                    <a href="/" class="h-full flex items-center justify-center">
                        <img src="assets/images/bru_gogreen_logo.png" alt="BRU Waste Bank"
                            class="h-[60%] hover:cursor-pointer">
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <button @click="scrollTo('about')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">รู้จักโครงการ</button>
                    <button @click="scrollTo('how-it-works')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">การทำงาน</button>
                    <button @click="scrollTo('video-guide')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">วิดีโอแนะนำ</button>
                    <button @click="scrollTo('rewards')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">ของรางวัล</button>
                    <button @click="scrollTo('leaderboard')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">อันดับคณะ</button>
                    <a href="/login"
                        class="bg-white border-2 border-green-600 text-green-600 hover:bg-green-50 hover:cursor-pointer hover:scale-105 active:scale-98 px-6 py-2 rounded-full font-semibold transition-all">
                        เข้าสู่ระบบ
                    </a>
                </div>

                <div class="md:hidden flex items-center">
                    <button @click="isMenuOpen = !isMenuOpen" class="text-gray-600">
                        <i x-show="!isMenuOpen" data-lucide="menu" class="w-6 h-6"></i>
                        <i x-show="isMenuOpen" x-cloak data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="isMenuOpen" x-collapse x-cloak
            class="md:hidden bg-white border-b border-gray-100 px-4 pt-2 pb-6 space-y-3 shadow-lg absolute w-full">
            <button @click="scrollTo('about')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">รู้จักโครงการ</button>
            <button @click="scrollTo('how-it-works')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">การทำงาน</button>
            <button @click="scrollTo('video-guide')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">วิดีโอแนะนำ</button>
            <button @click="scrollTo('rewards')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">ของรางวัล</button>
            <button @click="scrollTo('leaderboard')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">อันดับคณะ</button>
            <div class="pt-2">
                <button onclick="window.location.href='/login'"
                    class="w-full bg-green-600 text-white px-4 py-3 rounded-xl font-semibold cursor-pointer hover:scale-105 active:scale-98 hover:bg-green-700 active:bg-green-700">เข้าสู่ระบบ</button>
            </div>
        </div>
    </nav>

    <section id="about"
        class="scroll-mt-20 relative min-h-[calc(100vh-5rem)] overflow-hidden flex flex-col justify-center py-6 md:py-12">

        <div class="absolute inset-0 bg-gradient-to-b from-green-50/50 to-white -z-10"></div>
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-green-400/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -ml-20 w-80 h-80 bg-emerald-400/10 rounded-full blur-3xl"></div>

        <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8 text-center">
            <div
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-green-100 text-green-700 font-semibold text-sm mb-8">
                <span class="relative flex h-3 w-3">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                </span>
                ระบบธนาคารขยะเปิดให้บริการแล้ว
            </div>
            <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 tracking-tight mb-6">
                เปลี่ยนขยะให้เป็นแต้ม<br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-green-600 to-emerald-500">
                    สร้างโลกสีเขียวไปด้วยกัน
                </span>
            </h1>
            <p class="mt-4 text-xl text-gray-600 max-w-2xl mx-auto mb-10 leading-relaxed">
                เข้าร่วมโครงการธนาคารขยะ มหาวิทยาลัยราชภัฏบุรีรัมย์ คัดแยกขยะ สะสมแต้ม แลกของรางวัล
                และร่วมสร้างสังคมคาร์บอนต่ำ พร้อมเก็บชั่วโมงจิตอาสา
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <button onclick="window.location.href = '/register'"
                    class="px-8 py-4 bg-green-600 hover:bg-green-700 hover:cursor-pointer text-white rounded-xl font-bold text-lg shadow-lg shadow-green-600/30 transition-all hover:scale-105 active:scale-98 flex items-center justify-center gap-2">
                    สมัครสมาชิกเลย <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </button>
                <button @click="scrollTo('how-it-works')"
                    class="px-8 py-4 bg-white hover:bg-gray-50 hover:cursor-pointer text-gray-700 border border-gray-200 rounded-xl font-bold text-lg shadow-sm transition-all hover:scale-105 active:scale-98">
                    วิธีการทำงาน
                </button>
            </div>
        </div>

        <div class="max-w-6xl mx-auto w-full px-4 sm:px-6 lg:px-8 mt-8 md:mt-12 relative z-10">
            <div
                class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 p-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 divide-y md:divide-y-0 md:divide-x divide-gray-100 border border-gray-100">
                <template x-for="(stat, index) in stats" :key="index">
                    <div :class="{'pt-8 md:pt-0': index !== 0}" class="flex flex-col items-center text-center">
                        <div class="bg-gray-50 p-4 rounded-2xl mb-4" :class="stat.iconColor">
                            <i :data-lucide="stat.icon" class="w-8 h-8"></i>
                        </div>
                        <h3 class="text-3xl font-black text-gray-900 mb-1" x-text="stat.value"></h3>
                        <p class="text-sm text-gray-500 font-medium" x-text="stat.label"></p>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <section id="how-it-works"
        class="scroll-mt-20 bg-white min-h-[calc(100vh-5rem)] overflow-hidden flex flex-col justify-center py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 md:mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">เปลี่ยนพฤติกรรม เป็นรางวัลได้อย่างไร?</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    โมเดลการทำงานที่ออกแบบมาเพื่อให้นักศึกษาและบุคลากรมีส่วนร่วมได้ง่ายๆ
                    ผ่านแนวคิด Activity-based Volunteer Credit
                </p>
            </div>

            <div class="grid md:grid-cols-4 gap-8 relative">
                <div
                    class="hidden md:block absolute top-1/2 left-[10%] right-[10%] h-0.5 bg-gradient-to-r from-green-100 via-green-300 to-green-100 z-10 transform -translate-y-1/2 border-dashed border-t-2">
                </div>

                <div
                    class="z-15 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative hover:shadow-md transition-shadow group">
                    <div
                        class="w-16 h-16 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center mb-6 text-2xl font-bold mx-auto group-hover:scale-110 transition-transform">
                        1</div>
                    <h3 class="text-xl font-bold text-center mb-3">คัดแยกขยะ</h3>
                    <p class="text-gray-500 text-center text-sm">นักศึกษาคัดแยกขยะตามประเภท (พลาสติก, กระดาษ, แก้ว,
                        โลหะ)</p>
                </div>

                <div
                    class="z-15 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative hover:shadow-md transition-shadow group">
                    <div
                        class="w-16 h-16 bg-green-100 text-green-600 rounded-2xl flex items-center justify-center mb-6 text-2xl font-bold mx-auto group-hover:scale-110 transition-transform">
                        2</div>
                    <h3 class="text-xl font-bold text-center mb-3">ฝากที่จุดรับ</h3>
                    <p class="text-gray-500 text-center text-sm">นำขยะมาฝากที่จุดรับของคณะ
                        เจ้าหน้าที่ชั่งน้ำหนักและบันทึก</p>
                </div>

                <div
                    class="z-15 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative hover:shadow-md transition-shadow group">
                    <div
                        class="w-16 h-16 bg-yellow-100 text-yellow-600 rounded-2xl flex items-center justify-center mb-6 text-2xl font-bold mx-auto group-hover:scale-110 transition-transform">
                        3</div>
                    <h3 class="text-xl font-bold text-center mb-3">รับแต้ม & แบดจ์</h3>
                    <p class="text-gray-500 text-center text-sm">ระบบคำนวณแต้มขยะอัตโนมัติ พร้อมสะสมค่าประสบการณ์
                        (Level)</p>
                </div>

                <div
                    class="z-15 bg-white p-6 rounded-2xl shadow-sm border border-gray-100 relative hover:shadow-md transition-shadow group">
                    <div
                        class="w-16 h-16 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mb-6 text-2xl font-bold mx-auto group-hover:scale-110 transition-transform">
                        4</div>
                    <h3 class="text-xl font-bold text-center mb-3">แลกรางวัล / กยศ.</h3>
                    <p class="text-gray-500 text-center text-sm">ใช้แต้มแลกของจากศูนย์ใหญ่
                        หรือใช้เป็นหลักฐานปลดล็อกชั่วโมงจิตอาสา</p>
                </div>
            </div>

            <div
                class="mt-8 md:mt-12 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-3xl p-8 border border-blue-100 flex flex-col md:flex-row items-center gap-8">
                <div class="bg-white p-4 rounded-full shadow-sm">
                    <i data-lucide="clock" class="w-12 h-12 text-blue-500"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">โมเดลเก็บชั่วโมง กยศ. รูปแบบใหม่</h3>
                    <p class="text-gray-600">
                        แต้มจากธนาคารขยะ ทำหน้าที่เป็น <span
                            class="font-semibold text-blue-600">"ตัวกรองความตั้งใจ"</span> เมื่อสะสมแต้มถึงเกณฑ์
                        จะสามารถปลดล็อกสิทธิ์เข้าร่วมกิจกรรมจิตอาสาจริงของศูนย์ใหญ่หรือคณะได้
                        ชั่วโมงเกิดจากการลงมือทำจริง ไม่ใช่การนำขยะมาซื้อชั่วโมง
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section id="video-guide"
        class="scroll-mt-20 bg-gray-50 min-h-[calc(80vh)] overflow-hidden flex flex-col justify-center py-6 md:py-12">
        <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8 md:mb-12">
                <div class="inline-flex items-center justify-center p-3 bg-red-100 rounded-full mb-4">
                    <i data-lucide="square-play" class="w-8 h-8 text-red-600"></i>
                </div>
                <!-- <h2 class="text-3xl font-bold text-gray-900 mb-4">วิดีโอแนะนำระบบ</h2> -->
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    วิดิโอแนะนำกิจกรรม BRU Go Green
                </p>
            </div>
            <div class="max-w-7xl mx-auto w-full">
                <div class="w-full aspect-video rounded-2xl overflow-hidden shadow-xl border border-gray-200 bg-black">
                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/s-OtnUQglrs?si=mltqiwJ43FM2pud1"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                </div>
            </div>
        </div>
    </section>

    <section class="scroll-mt-20 py-6 md:py-12 bg-gray-900 text-white relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden opacity-10">
            <div
                class="absolute top-[-10%] left-[-10%] w-96 h-96 bg-green-500 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob">
            </div>
            <div
                class="absolute top-[-10%] right-[-10%] w-96 h-96 bg-yellow-500 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute bottom-[-20%] left-[20%] w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center p-3 bg-gray-800 rounded-full mb-4">
                    <i data-lucide="shield-check" class="w-8 h-8 text-yellow-400"></i>
                </div>
                <h2 class="text-3xl font-bold mb-4">สนุกไปกับการรักษ์โลก (Gamification)</h2>
                <p class="text-gray-400 max-w-2xl mx-auto text-lg">
                    สะสมแต้ม เลื่อนระดับ และปลดล็อกเหรียญตราเกียรติยศ เพื่อเป็นผู้นำด้านสิ่งแวดล้อม
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div
                    class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 p-6 rounded-2xl text-center hover:bg-gray-800 transition-colors">
                    <div
                        class="w-20 h-20 mx-auto bg-green-900/50 rounded-full flex items-center justify-center border-4 border-green-500 mb-4 shadow-[0_0_15px_rgba(34,197,94,0.3)]">
                        <span class="text-4xl">♻️</span>
                    </div>
                    <h4 class="font-bold text-lg text-white mb-1">พลาสติกมาสเตอร์</h4>
                    <p class="text-xs text-green-400 font-medium">Recycling Badge</p>
                    <p class="text-xs text-gray-400 mt-2">รีไซเคิลพลาสติกครบ 50 กก.</p>
                </div>
                <div
                    class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 p-6 rounded-2xl text-center hover:bg-gray-800 transition-colors">
                    <div
                        class="w-20 h-20 mx-auto bg-emerald-900/50 rounded-full flex items-center justify-center border-4 border-emerald-500 mb-4 shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                        <span class="text-4xl">🌱</span>
                    </div>
                    <h4 class="font-bold text-lg text-white mb-1">ผู้พิทักษ์สีเขียว</h4>
                    <p class="text-xs text-emerald-400 font-medium">Green Impact</p>
                    <p class="text-xs text-gray-400 mt-2">ลดคาร์บอนครบ 100 kgCO₂e</p>
                </div>
                <div
                    class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 p-6 rounded-2xl text-center hover:bg-gray-800 transition-colors">
                    <div
                        class="w-20 h-20 mx-auto bg-blue-900/50 rounded-full flex items-center justify-center border-4 border-blue-500 mb-4 shadow-[0_0_15px_rgba(59,130,246,0.3)]">
                        <span class="text-4xl">🤝</span>
                    </div>
                    <h4 class="font-bold text-lg text-white mb-1">จิตอาสาดีเด่น</h4>
                    <p class="text-xs text-blue-400 font-medium">Community Badge</p>
                    <p class="text-xs text-gray-400 mt-2">เข้าร่วมกิจกรรม 5 ครั้ง</p>
                </div>
                <div
                    class="bg-gray-800/50 backdrop-blur-sm border border-gray-700 p-6 rounded-2xl text-center hover:bg-gray-800 transition-colors">
                    <div
                        class="w-20 h-20 mx-auto bg-yellow-900/50 rounded-full flex items-center justify-center border-4 border-yellow-500 mb-4 shadow-[0_0_15px_rgba(234,179,8,0.3)]">
                        <span class="text-4xl">⭐</span>
                    </div>
                    <h4 class="font-bold text-lg text-white mb-1">ฮีโร่เลเวล 10</h4>
                    <p class="text-xs text-yellow-400 font-medium">Achievement</p>
                    <p class="text-xs text-gray-400 mt-2">แต้มสะสมรวม 10,000 แต้ม</p>
                </div>
            </div>
        </div>
    </section>

    <section id="rewards"
        class="scroll-mt-20 bg-gray-50 min-h-[calc(100vh-5rem)] overflow-hidden flex flex-col justify-center py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                        <i data-lucide="gift" class="text-rose-500 w-8 h-8"></i> รายการของรางวัล
                    </h2>
                    <p class="text-gray-600 max-w-2xl text-lg">
                        ใช้แต้มขยะที่คุณสะสม มาแลกรับของรางวัลจากศูนย์ใหญ่ได้ทันที
                    </p>
                </div>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <template x-for="reward in rewards" :key="reward.donation_item_id">
                    <div
                        class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl transition-all hover:-translate-y-1 group flex flex-col">
                        <div
                            class="w-full h-40 bg-gray-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-105 transition-transform overflow-hidden">
                            <img :src="reward.donation_item_image ? `assets/images/donation_items/${reward.donation_item_image}` : 'https://placehold.co/400x400/e2e8f0/a0aec0?text=BRU'"
                                :alt="reward.donation_item_name" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow">
                            <h3 class="font-bold text-lg text-gray-900 mb-1" x-text="reward.donation_item_name"></h3>
                        </div>

                        <div class="flex items-center justify-center pt-4 border-t border-gray-50">

                            <template
                                x-if="reward.donation_item_discount_point && reward.donation_item_discount_point > 0">
                                <div class="flex items-baseline gap-2">
                                    <span class="font-bold text-gray-400 line-through"
                                        x-text="parseInt(reward.donation_item_redeem_point).toLocaleString()"></span>
                                    <span class="font-bold text-green-600 text-xl"
                                        x-text="`${parseInt(reward.donation_item_discount_point).toLocaleString()} แต้ม`"></span>
                                </div>
                            </template>

                            <template
                                x-if="!reward.donation_item_discount_point || reward.donation_item_discount_point == 0">
                                <div class="font-bold text-green-600 text-center text-xl">
                                    <span
                                        x-text="`${parseInt(reward.donation_item_redeem_point).toLocaleString()} แต้ม`"></span>
                                </div>
                            </template>

                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <section id="leaderboard"
        class="scroll-mt-20 bg-white min-h-[calc(100vh-5rem)] overflow-hidden flex flex-col justify-center py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center p-3 bg-yellow-100 rounded-full mb-4">
                    <i data-lucide="trophy" class="w-8 h-8 text-yellow-600"></i>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4"
                    x-text="leaderboardType === 'faculty' ? 'กระดานผู้นำระดับคณะ' : (leaderboardType === 'major' ? 'กระดานผู้นำระดับสาขา' : 'กระดานผู้นำระดับบุคคล')">
                    กระดานผู้นำระดับคณะ</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg mb-8">
                    การแข่งขันเชิงสร้างสรรค์
                    เพื่อค้นหาสุดยอดคณะและบุคคลที่มีส่วนร่วมในการจัดการขยะและลดคาร์บอนได้มากที่สุด
                </p>

                <!-- Toggle Switch -->
                <div class="flex justify-center">
                    <div class="bg-gray-100 p-1.5 rounded-full inline-flex relative shadow-inner">
                        <button
                            @click="leaderboardType = 'faculty'; setTimeout(() => { if(window.lucide) lucide.createIcons(); }, 100);"
                            :class="{'bg-white shadow text-gray-900': leaderboardType === 'faculty', 'text-gray-500 hover:text-gray-700': leaderboardType !== 'faculty'}"
                            class="px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                            <i data-lucide="building-2" class="w-4 h-4"></i> ระดับคณะ
                        </button>
                        <button
                            @click="leaderboardType = 'major'; setTimeout(() => { if(window.lucide) lucide.createIcons(); }, 100);"
                            :class="{'bg-white shadow text-gray-900': leaderboardType === 'major', 'text-gray-500 hover:text-gray-700': leaderboardType !== 'major'}"
                            class="px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                            <i data-lucide="book-open" class="w-4 h-4"></i> ระดับสาขา
                        </button>
                        <button
                            @click="leaderboardType = 'member'; setTimeout(() => { if(window.lucide) lucide.createIcons(); }, 100);"
                            :class="{'bg-white shadow text-gray-900': leaderboardType === 'member', 'text-gray-500 hover:text-gray-700': leaderboardType !== 'member'}"
                            class="px-6 py-2.5 rounded-full text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                            <i data-lucide="user" class="w-4 h-4"></i> ระดับบุคคล
                        </button>
                    </div>
                </div>
            </div>

            <div class="relative group">
                <!-- Left Arrow -->
                <button
                    @click="leaderboardType = leaderboardType === 'member' ? 'major' : 'faculty'; setTimeout(() => { if(window.lucide) lucide.createIcons(); }, 100);"
                    class="absolute left-0 ml-0 md:-ml-6 top-1/2 -translate-y-1/2 bg-white border border-gray-200 text-gray-800 p-2 md:p-3 rounded-full shadow-lg hover:bg-gray-50 z-20 transition-all duration-300"
                    :class="leaderboardType === 'faculty' ? 'opacity-0 pointer-events-none scale-95' : 'opacity-100 cursor-pointer scale-100'">
                    <i data-lucide="chevron-left" class="w-6 h-6 text-green-600"></i>
                </button>

                <!-- Right Arrow -->
                <button
                    @click="leaderboardType = leaderboardType === 'faculty' ? 'major' : 'member'; setTimeout(() => { if(window.lucide) lucide.createIcons(); }, 100);"
                    class="absolute right-0 mr-0 md:-mr-6 top-1/2 -translate-y-1/2 bg-white border border-gray-200 text-gray-800 p-2 md:p-3 rounded-full shadow-lg hover:bg-gray-50 z-20 transition-all duration-300"
                    :class="leaderboardType === 'member' ? 'opacity-0 pointer-events-none scale-95' : 'opacity-100 cursor-pointer scale-100'">
                    <i data-lucide="chevron-right" class="w-6 h-6 text-green-600"></i>
                </button>

                <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden relative">
                    <!-- Carousel Track -->
                    <div class="flex transition-transform duration-500 ease-out"
                        :style="`transform: translateX(-${['faculty', 'major', 'member'].indexOf(leaderboardType) * 100}%)`">

                        <!-- Faculty Table Slide -->
                        <div class="w-full flex-shrink-0">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr
                                            class="bg-gray-50 text-gray-500 text-sm font-semibold uppercase tracking-wider">
                                            <th class="py-5 px-6">อันดับ</th>
                                            <th class="py-5 px-6">คณะ</th>
                                            <th class="py-5 px-6 text-right">แต้มสะสมรวม</th>
                                            <th class="py-5 px-6 text-right hidden sm:table-cell">ปริมาณขยะ</th>
                                            <th class="py-5 px-6 text-right hidden md:table-cell">คาร์บอนที่ลดได้ (CO₂e)
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <template x-for="item in facultyLeaderboard" :key="'fac-'+item.rank">
                                            <tr class="hover:bg-gray-50/50 transition-colors">
                                                <td class="py-5 px-6">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm"
                                                        :class="{
                                         'bg-yellow-100 text-yellow-700': item.rank === 1,
                                         'bg-gray-100 text-gray-600': item.rank === 2,
                                         'bg-orange-100 text-orange-700': item.rank === 3,
                                         'bg-gray-50 text-gray-400': item.rank > 3
                                       }" x-text="item.rank">
                                                    </div>
                                                </td>
                                                <td class="py-5 px-6 font-bold text-gray-900" x-text="item.name"></td>
                                                <td class="py-5 px-6 text-right font-bold text-green-600"
                                                    x-text="item.points"></td>
                                                <td class="py-5 px-6 text-right text-gray-500 hidden sm:table-cell"
                                                    x-text="item.weight"></td>
                                                <td
                                                    class="py-5 px-6 text-right text-emerald-600 font-medium hidden md:table-cell">
                                                    <span class="flex items-center justify-end gap-1"><i
                                                            data-lucide="leaf" class="w-4 h-4"></i> <span
                                                            x-text="item.carbon"></span></span>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Major Table Slide -->
                        <div class="w-full flex-shrink-0">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr
                                            class="bg-gray-50 text-gray-500 text-sm font-semibold uppercase tracking-wider">
                                            <th class="py-5 px-6">อันดับ</th>
                                            <th class="py-5 px-6">สาขา</th>
                                            <th class="py-5 px-6 text-right">แต้มสะสมรวม</th>
                                            <th class="py-5 px-6 text-right hidden sm:table-cell">ปริมาณขยะ</th>
                                            <th class="py-5 px-6 text-right hidden md:table-cell">คาร์บอนที่ลดได้ (CO₂e)
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <template x-for="item in majorLeaderboard" :key="'maj-'+item.rank">
                                            <tr class="hover:bg-gray-50/50 transition-colors">
                                                <td class="py-5 px-6">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm"
                                                        :class="{
                                         'bg-yellow-100 text-yellow-700': item.rank === 1,
                                         'bg-gray-100 text-gray-600': item.rank === 2,
                                         'bg-orange-100 text-orange-700': item.rank === 3,
                                         'bg-gray-50 text-gray-400': item.rank > 3
                                       }" x-text="item.rank">
                                                    </div>
                                                </td>
                                                <td class="py-5 px-6 font-bold text-gray-900" x-text="item.name"></td>
                                                <td class="py-5 px-6 text-right font-bold text-green-600"
                                                    x-text="item.points"></td>
                                                <td class="py-5 px-6 text-right text-gray-500 hidden sm:table-cell"
                                                    x-text="item.weight"></td>
                                                <td
                                                    class="py-5 px-6 text-right text-emerald-600 font-medium hidden md:table-cell">
                                                    <span class="flex items-center justify-end gap-1"><i
                                                            data-lucide="leaf" class="w-4 h-4"></i> <span
                                                            x-text="item.carbon"></span></span>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Member Table Slide -->
                        <div class="w-full flex-shrink-0">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr
                                            class="bg-gray-50 text-gray-500 text-sm font-semibold uppercase tracking-wider">
                                            <th class="py-5 px-6">อันดับ</th>
                                            <th class="py-5 px-6">ชื่อ-สกุล</th>
                                            <th class="py-5 px-6 text-right">แต้มสะสมรวม</th>
                                            <th class="py-5 px-6 text-right hidden sm:table-cell">ปริมาณขยะ</th>
                                            <th class="py-5 px-6 text-right hidden md:table-cell">คาร์บอนที่ลดได้ (CO₂e)
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        <template x-for="item in memberLeaderboard" :key="'mem-'+item.rank">
                                            <tr class="hover:bg-gray-50/50 transition-colors">
                                                <td class="py-5 px-6">
                                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm"
                                                        :class="{
                                         'bg-yellow-100 text-yellow-700': item.rank === 1,
                                         'bg-gray-100 text-gray-600': item.rank === 2,
                                         'bg-orange-100 text-orange-700': item.rank === 3,
                                         'bg-gray-50 text-gray-400': item.rank > 3
                                       }" x-text="item.rank">
                                                    </div>
                                                </td>
                                                <td class="py-5 px-6 font-bold text-gray-900" x-text="item.name"></td>
                                                <td class="py-5 px-6 text-right font-bold text-green-600"
                                                    x-text="item.points"></td>
                                                <td class="py-5 px-6 text-right text-gray-500 hidden sm:table-cell"
                                                    x-text="item.weight"></td>
                                                <td
                                                    class="py-5 px-6 text-right text-emerald-600 font-medium hidden md:table-cell">
                                                    <span class="flex items-center justify-end gap-1"><i
                                                            data-lucide="leaf" class="w-4 h-4"></i> <span
                                                            x-text="item.carbon"></span></span>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Carousel Indicators (Dots) -->
                    <div class="bg-gray-50 p-4 text-center border-t border-gray-100 flex justify-center gap-2">
                        <button
                            @click="leaderboardType = 'faculty'; setTimeout(() => { if(window.lucide) lucide.createIcons(); }, 100);"
                            :class="leaderboardType === 'faculty' ? 'w-8 bg-green-500' : 'w-2 bg-gray-300 hover:bg-gray-400'"
                            class="h-2 rounded-full transition-all duration-300 cursor-pointer"></button>
                        <button
                            @click="leaderboardType = 'major'; setTimeout(() => { if(window.lucide) lucide.createIcons(); }, 100);"
                            :class="leaderboardType === 'major' ? 'w-8 bg-green-500' : 'w-2 bg-gray-300 hover:bg-gray-400'"
                            class="h-2 rounded-full transition-all duration-300 cursor-pointer"></button>
                        <button
                            @click="leaderboardType = 'member'; setTimeout(() => { if(window.lucide) lucide.createIcons(); }, 100);"
                            :class="leaderboardType === 'member' ? 'w-8 bg-green-500' : 'w-2 bg-gray-300 hover:bg-gray-400'"
                            class="h-2 rounded-full transition-all duration-300 cursor-pointer"></button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-gray-900 text-gray-400 py-12 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-8 items-center">
                <div class="flex flex-col justify-center items-center">
                    <div class="flex items-center gap-3 mb-4">
                        <div>
                            <img class="h-10" src="assets/images/waste_bankFullLogo.png" alt="">
                            <!-- <h1 class="font-bold text-lg text-white">BRU Waste Bank</h1> -->
                        </div>
                        <div>
                            <img class="h-10" src="assets/images/bru_gogreen_logo.png" alt="">
                            <!-- <h1 class="font-bold text-lg text-white">BRU Waste Bank</h1> -->
                        </div>
                    </div>
                    <p class="text-sm">
                        โครงการธนาคารขยะ มหาวิทยาลัยราชภัฏบุรีรัมย์<br />
                        สร้างสังคมคาร์บอนต่ำอย่างยั่งยืน
                    </p>
                </div>
                <div class="text-center">
                    <p class="text-sm">
                        © <span x-text="new Date().getFullYear()"></span>
                        มหาวิทยาลัยราชภัฏบุรีรัมย์.<br />สงวนลิขสิทธิ์.
                    </p>
                </div>
                <div class="flex justify-center md:justify-end gap-4">
                    <button class="hover:text-white transition-all cursor-pointer">ติดต่อแอดมิน</button>
                    <button class="hover:text-white transition-all cursor-pointer">นโยบายความเป็นส่วนตัว</button>
                </div>
            </div>
        </div>
    </footer>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('wasteBankApp', () => ({
            isMenuOpen: false,
            stats: [
                { label: 'สมาชิกที่เข้าร่วม (คน.)', value: '0', icon: 'users', iconColor: 'text-green-500' },
                { label: 'นักศึกษา (คน.)', value: '0', icon: 'graduation-cap', iconColor: 'text-green-500' },
                { label: 'อาจารย์/บุคลากร (คน.)', value: '0', icon: 'id-card-lanyard', iconColor: 'text-green-500' },
                { label: 'แต้มที่แจกจ่ายแล้ว (แต้ม)', value: '0', icon: 'award', iconColor: 'text-yellow-500' },
            ],
            rewards: [],
            leaderboardType: 'faculty',
            facultyLeaderboard: [],
            majorLeaderboard: [],
            memberLeaderboard: [],

            init() {
                this.fetchStats();
                this.fetchRewards();
                this.fetchFacultyLeaderboard();
                this.fetchMajorLeaderboard();
                this.fetchMemberLeaderboard();
            },

            async fetchStats() {
                try {
                    const response = await fetch('/api/statistics');
                    const result = await response.json();
                    if (result.success && result.data) {
                        const data = result.data;
                        this.stats[0].value = parseFloat(data.member_count || 0).toLocaleString();
                        this.stats[1].value = parseFloat(data.user_count || 0).toLocaleString();
                        this.stats[2].value = parseFloat(data.professor_employee_count || 0).toLocaleString();
                        this.stats[3].value = parseInt(data.total_point || 0).toLocaleString();
                    }
                } catch (error) {
                    console.error('Error fetching stats:', error);
                }
            },

            async fetchRewards() {
                try {
                    const response = await fetch('/api/donations/items/available?limit=8');
                    const result = await response.json();
                    if (result.success && result.data) {
                        this.rewards = result.data;
                    }
                } catch (error) {
                    console.error('Error fetching rewards:', error);
                }
            },

            async fetchFacultyLeaderboard() {
                try {
                    const response = await fetch('/api/leaders/faculty?limit=10&page=1');
                    const result = await response.json();
                    if (result.success && result.data) {
                        this.facultyLeaderboard = result.data.map((item, index) => ({
                            rank: index + 1,
                            name: item.faculty_name,
                            points: parseInt(item.total_point).toLocaleString(),
                            weight: `${parseFloat(item.total_weight).toLocaleString('en-US', { maximumFractionDigits: 2 })} กก.`,
                            carbon: parseFloat(item.total_co2e).toLocaleString('en-US', { maximumFractionDigits: 2 })
                        }));
                        setTimeout(() => { if (window.lucide) lucide.createIcons(); }, 100);
                    }
                } catch (error) {
                    console.error('Error fetching faculty leaderboard:', error);
                }
            },

            async fetchMajorLeaderboard() {
                try {
                    const response = await fetch('/api/leaders/major?limit=10&page=1');
                    const result = await response.json();
                    if (result.success && result.data) {
                        this.majorLeaderboard = result.data.map((item, index) => ({
                            rank: index + 1,
                            name: item.major_name,
                            points: parseInt(item.total_point).toLocaleString(),
                            weight: `${parseFloat(item.total_weight).toLocaleString('en-US', { maximumFractionDigits: 2 })} กก.`,
                            carbon: parseFloat(item.total_co2e).toLocaleString('en-US', { maximumFractionDigits: 2 })
                        }));
                        setTimeout(() => { if (window.lucide) lucide.createIcons(); }, 100);
                    }
                } catch (error) {
                    console.error('Error fetching major leaderboard:', error);
                }
            },

            async fetchMemberLeaderboard() {
                try {
                    const response = await fetch('/api/leaders/member?limit=10&page=1');
                    const result = await response.json();
                    if (result.success && result.result) {
                        this.memberLeaderboard = result.result.map((item, index) => ({
                            rank: index + 1,
                            name: item.member_name || item.name || 'ไม่ระบุชื่อ',
                            points: parseInt(item.total_point).toLocaleString(),
                            weight: `${parseFloat(item.total_weight).toLocaleString('en-US', { maximumFractionDigits: 2 })} กก.`,
                            carbon: parseFloat(item.total_co2).toLocaleString('en-US', { maximumFractionDigits: 2 })
                        }));
                        setTimeout(() => { if (window.lucide) lucide.createIcons(); }, 100);
                    }
                } catch (error) {
                    console.error('Error fetching leaderboard:', error);
                }
            },

            scrollTo(id) {
                const el = document.getElementById(id);
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth' });
                    this.isMenuOpen = false;
                }
            }
        }))
    })
</script>