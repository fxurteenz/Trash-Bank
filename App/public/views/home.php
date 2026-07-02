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
                    <button @click="scrollTo('video-guide')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">วิดีโอแนะนำ</button>
                    <!-- <button @click="scrollTo('news')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">ข่าวสารและกิจกรรม</button> -->
                    <button @click="scrollTo('rewards')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">ของรางวัล</button>
                    <button @click="scrollTo('leaderboard')"
                        class="text-gray-600 hover:text-green-600 font-medium transition-colors cursor-pointer">กระดานผู้นำ</button>
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
            <button @click="scrollTo('video-guide')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">วิดีโอแนะนำ</button>
            <!-- <button @click="scrollTo('how-it-works')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">การทำงาน</button> -->
            <button @click="scrollTo('rewards')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">ของรางวัล</button>
            <button @click="scrollTo('leaderboard')"
                class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-green-50 hover:text-green-600 rounded-lg">กระดานผู้นำ</button>
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
                <button onclick="window.location.href = '/login'"
                    class="px-8 py-4 bg-white hover:bg-gray-50 hover:cursor-pointer text-gray-700 border border-gray-200 rounded-xl font-bold text-lg shadow-sm transition-all hover:scale-105 active:scale-98">
                    เข้าสู่ระบบ
                </button>
                <button onclick="window.location.href = '/register'"
                    class="px-8 py-4 bg-green-600 hover:bg-green-700 hover:cursor-pointer text-white rounded-xl font-bold text-lg shadow-lg shadow-green-600/30 transition-all hover:scale-105 active:scale-98 flex items-center justify-center gap-2">
                    สมัครสมาชิกเลย <i data-lucide="arrow-right" class="w-5 h-5"></i>
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

    <section id="video-guide"
        class="scroll-mt-20 bg-white min-h-[calc(80vh)] overflow-hidden flex flex-col justify-center py-6 md:py-12">
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



    <!-- <section id="how-it-works"
        class="scroll-mt-20 bg-gray-50 min-h-[calc(100vh-5rem)] overflow-hidden flex flex-col justify-center py-6 md:py-12">
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
                    <p class="text-gray-500 text-center text-sm">นักศึกษาคัดแยกขยะตามประเภท (พลาสติก, กระดาษ,
                        แก้ว,
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
                    <p class="text-gray-500 text-center text-sm">ระบบคำนวณแต้มขยะอัตโนมัติ
                        พร้อมสะสมค่าประสบการณ์
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
    </section> -->

    <!-- <section class="scroll-mt-20 py-6 md:py-12 bg-gray-900 text-white relative overflow-hidden">
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
    </section> -->

    <section id="rewards"
        class="scroll-mt-20 bg-gray-50 min-h-[calc(100vh-5rem)] overflow-hidden flex flex-col justify-center py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-24">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                        <i data-lucide="gift" class="text-rose-500 w-8 h-8"></i> ของรางวัลที่แลกได้
                    </h2>
                    <p class="text-gray-600 max-w-2xl text-lg">
                        ใช้แต้มขยะที่คุณสะสม มาแลกรับของรางวัลจากศูนย์ใหญ่ได้ทันที
                    </p>
                </div>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="reward in rewards" :key="reward.donation_item_id">
                    <div
                        class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-xl transition-all hover:-translate-y-1 group flex flex-col">
                        <div
                            class="w-full h-40 bg-gray-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-105 transition-transform overflow-hidden">
                            <img :src="reward.donation_item_image ? `assets/images/donation_items/${reward.donation_item_image}` : 'https://placehold.co/400x400/e2e8f0/a0aec0?text=BRU'"
                                :alt="reward.donation_item_name" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-grow">
                            <h3 class="font-bold text-lg text-gray-900 mb-1" x-text="reward.donation_item_name">
                            </h3>
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

    <section id="leaderboard" class="py-12 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
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
            </div>

            <div class="rounded-full p-2 md:p-3 flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="bg-gray-100 p-1.5 rounded-full inline-flex relative shadow-inner">
                    <button @click="setLeaderboardType('faculty')"
                        :class="leaderboardType === 'faculty' ? 'bg-white shadow text-emerald-700 font-medium' : 'text-gray-400 hover:text-emerald-700 font-medium'"
                        class="px-6 py-2 rounded-full text-sm transition-all whitespace-nowrap flex-1 text-center flex gap-2 items-center cursor-pointer">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                        ระดับคณะ
                    </button>
                    <button @click="setLeaderboardType('major')"
                        :class="leaderboardType === 'major' ? 'bg-white shadow text-emerald-700 font-medium' : 'text-gray-400 hover:text-emerald-700 font-medium'"
                        class="px-6 py-2 rounded-full text-sm transition-all whitespace-nowrap flex-1 text-center flex gap-2 items-center cursor-pointer">
                        <i data-lucide="school" class="w-4 h-4"></i>
                        ระดับสาขา
                    </button>
                    <button @click="setLeaderboardType('member')"
                        :class="leaderboardType === 'member' ? 'bg-white shadow text-emerald-700 font-medium' : 'text-gray-400 hover:text-emerald-700 font-medium'"
                        class="px-6 py-2 rounded-full text-sm transition-all whitespace-nowrap flex-1 text-center flex gap-2 items-center cursor-pointer">
                        <i data-lucide="users-round" class="w-4 h-4"></i>
                        ระดับบุคคล
                    </button>
                </div>

                <div class="bg-gray-100 p-1.5 rounded-full inline-flex relative shadow-inner">
                    <button @click="setSortType('point')"
                        :class="{'bg-white shadow text-sky-600': sortType === 'point', 'text-gray-400 hover:text-sky-600': sortType !== 'point'}"
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="award" class="w-4 h-4"></i> แต้มขยะ
                    </button>
                     <button @click="setSortType('goodness')"
                        :class="{'bg-white shadow text-yellow-600': sortType === 'goodness', 'text-gray-400 hover:text-yellow-600': sortType !== 'goodness'}"
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="smile" class="w-4 h-4"></i> แต้มความดี
                    </button>
                    <button @click="setSortType('social')"
                        :class="{'bg-white shadow text-red-600': sortType === 'social', 'text-gray-400 hover:text-red-700': sortType !== 'social'}"
                        class="px-6 py-2.5 rounded-full text-sm font-medium transition-all duration-300 flex items-center justify-center gap-2 cursor-pointer">
                        <i data-lucide="users" class="w-4 h-4"></i> แต้มสังคม
                    </button>
                </div>
            </div>

            <div class="flex flex-col md:flex-row justify-center items-end gap-6 mb-16 px-4 md:px-12 mt-25"
                x-show="activeLeaderboard.length >= 3">

                <div class="w-full md:w-1/3 order-2 md:order-1 relative group mt-16 md:mt-0">
                    <div class="absolute -top-10 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center">
                        <div class="relative">
                            <div
                                class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-gray-800 flex items-center justify-center overflow-hidden">
                                <template x-if="leaderboardType === 'member'">
                                    <span class="text-4xl font-bold text-blue-300"
                                        x-text="getFirstThaiChar(activeLeaderboard[1]?.name) || ''"></span>
                                </template>
                                <div x-show="leaderboardType !== 'member'">
                                    <i data-lucide="building-2" class="w-10 h-10 text-blue-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="bg-white rounded-2xl shadow-lg border border-gray-100 pt-16 pb-8 px-6 text-center transition-transform group-hover:-translate-y-2">
                        <span
                            class="inline-block px-3 py-1 bg-gray-100 text-gray-400 text-[8px] font-bold rounded-full mb-4">อันดับที่
                            2</span>
                        <h3 class="font-bold text-gray-900 text-xl line-clamp-1"
                            x-text="activeLeaderboard[1]?.name || '...'"></h3>
                        <template x-if="leaderboardType === 'major'">
                            <span class="text-gray-700 text-sm mb-1 line-clamp-1"
                                x-text="activeLeaderboard[1]?.faculty_name || '...'">
                            </span>
                        </template>
                        <div class="text-2xl font-black"
                            :class="sortType === 'point' ? 'text-sky-500' : sortType === 'social' ? 'text-red-600' : 'text-yellow-600'"
                            x-text="getSortValue(activeLeaderboard[1])">
                        </div>
                        <div class="text-xs text-gray-400 font-medium uppercase tracking-wider" x-text="getSortLabel()">
                        </div>

                    </div>
                </div>

                <div class="w-full md:w-1/3 order-1 md:order-2 relative z-10 group mt-16 md:mt-0">
                    <div class="absolute -top-30 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center">
                        <i data-lucide="crown" class="w-8 h-8 text-yellow-500 mb-1 drop-shadow-md animate-bounce "></i>
                        <div class="relative">
                            <div
                                class="w-32 h-32 rounded-full border-4 border-yellow-400 shadow-xl bg-gray-900 flex items-center justify-center overflow-hidden ring-4 ring-white">
                                <template x-if="leaderboardType === 'member'">
                                    <span class="text-5xl font-bold text-yellow-400"
                                        x-text="getFirstThaiChar(activeLeaderboard[0]?.name) || '?'"></span>
                                </template>
                                <div x-show="leaderboardType !== 'member'">
                                    <i data-lucide="trophy" class="w-14 h-14 text-yellow-500"></i>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div
                        class="bg-white rounded-t-3xl rounded-b-2xl border border-gray-100 shadow-xl pt-20 pb-10 px-6 text-center transform md:-translate-y-6 transition-transform group-hover:-translate-y-8 relative">
                        <span
                            class="inline-block px-3 py-1 bg-yellow-300/70 text-yellow-700 text-[8px] font-bold rounded-full mb-4">
                            อันดับที่ 1
                        </span>
                        <h3 class="font-bold text-gray-900 text-2xl line-clamp-1"
                            x-text="activeLeaderboard[0]?.name || '...'">
                        </h3>
                        <template x-if="leaderboardType === 'major'">
                            <span class="text-gray-700 text-sm mb-1 line-clamp-1"
                                x-text="activeLeaderboard[0]?.faculty_name || '...'">
                            </span>
                        </template>
                        <div class="text-3xl font-black"
                            :class="sortType === 'point' ? 'text-sky-500' : sortType === 'social' ? 'text-red-600' : 'text-yellow-600'"
                            x-text="getSortValue(activeLeaderboard[0])">
                        </div>
                        <div class="text-xs text-gray-400 font-medium uppercase tracking-wider" x-text="getSortLabel()">
                        </div>

                    </div>
                </div>

                <div class="w-full md:w-1/3 order-3 relative group mt-16 md:mt-0">
                    <div class="absolute -top-10 left-1/2 -translate-x-1/2 z-20 flex flex-col items-center">
                        <div class="relative">
                            <div
                                class="w-24 h-24 rounded-full border-4 border-white shadow-lg bg-gray-800 flex items-center justify-center overflow-hidden">
                                <template x-if="leaderboardType === 'member'">
                                    <span class="text-4xl font-bold text-orange-300"
                                        x-text="getFirstThaiChar(activeLeaderboard[2]?.name) || '?'"></span>
                                </template>
                                <div x-show="leaderboardType !== 'member'">
                                    <i data-lucide="medal" class="w-10 h-10 text-orange-300"></i>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div
                        class="bg-white rounded-2xl shadow-lg border border-gray-100 pt-16 pb-8 px-6 text-center transition-transform group-hover:-translate-y-2">
                        <span
                            class="inline-block px-3 py-1 bg-red-800/80 text-white text-[8px] font-bold rounded-full mb-4">อันดับที่
                            3</span>

                        <h3 class="font-bold text-gray-900 text-lg line-clamp-1"
                            x-text="activeLeaderboard[2]?.name || '...'">
                        </h3>
                        <template x-if="leaderboardType === 'major'">
                            <span class="text-gray-700 text-sm mb-1 line-clamp-1"
                                x-text="activeLeaderboard[2]?.faculty_name || '...'">
                            </span>
                        </template>
                        <div class="text-2xl font-black"
                            :class="sortType === 'point' ? 'text-sky-500' : sortType === 'social' ? 'text-red-600' : 'text-yellow-600'"
                            x-text="getSortValue(activeLeaderboard[2])">
                        </div>
                        <div class="text-xs text-gray-400 font-medium uppercase tracking-wider" x-text="getSortLabel()">
                        </div>

                    </div>
                </div>

            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-x-auto"
                x-show="activeLeaderboard.length > 3">
                <table class="w-full text-left">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-4 text-gray-600 font-bold text-sm uppercase w-5 tracking-wider">อันดับ
                            </th>
                            <th class="px-6 py-4 text-gray-600 font-bold text-sm uppercase tracking-wider"
                                x-text="leaderboardType === 'faculty' ? 'คณะ' : (leaderboardType === 'major' ? 'สาขา' : 'ชื่อ-สกุล')">
                            </th>
                            <th class="px-6 py-4 text-gray-600 font-bold text-sm uppercase tracking-wider text-end"
                                x-text="getSortLabel()"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <template x-for="(item, index) in activeLeaderboard.slice(3)" :key="item.rank">
                            <tr class="hover:bg-blue-50/50 transition-colors group">
                                <td class="px-6 py-4 text-center">
                                    <span class="text-gray-400 font-bold text-lg w-6 text-center font-mono"
                                        x-text="item.rank"></span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-sm md:text-base group-hover:text-blue-700 transition-colors"
                                            x-text="item.name"></h4>
                                        <template x-if="leaderboardType !== 'member'">
                                            <p class="text-xs text-gray-500 mt-0.5 transition-all duration-300 ease-out transform">
                                                ปริมาณขยะ: <span x-text="item.weight"></span> | ลดคาร์บอน: <span
                                                    x-text="item.goodness"></span> CO₂e
                                            </p>
                                        </template>
                                        <template x-if="leaderboardType === 'member'">
                                            <p class="text-xs text-gray-500 mt-0.5 transition-all duration-300 ease-out transform">
                                                คณะ: <span x-text="item.fname"></span> | สาขา: <span
                                                    x-text="item.mname"></span>
                                            </p>
                                        </template>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-end">
                                    <div class="text-lg font-black transition-all duration-300 ease-in-out transform"
                                        :class="sortType === 'point' ? 'text-sky-500' : sortType === 'social' ? 'text-red-600' : 'text-yellow-600'"
                                        x-text="getSortValue(item)"></div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div class="p-4 bg-gray-50 text-center text-xs text-gray-500 border-t border-gray-100 italic">
                    แสดงข้อมูลอันดับ 4 - 10 จากทั้งหมด
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
                { label: 'สมาชิกที่เข้าร่วม (คน)', value: '0', icon: 'users', iconColor: 'text-green-500' },
                { label: 'นักศึกษา (คน)', value: '0', icon: 'graduation-cap', iconColor: 'text-green-500' },
                { label: 'อาจารย์/บุคลากร (คน)', value: '0', icon: 'id-card-lanyard', iconColor: 'text-green-500' },
                { label: 'แต้มที่แจกจ่ายแล้ว (แต้ม)', value: '0', icon: 'award', iconColor: 'text-yellow-500' },
            ],
            rewards: [],
            leaderboardType: 'faculty',
            facultyLeaderboard: [],
            majorLeaderboard: [],
            memberLeaderboard: [],
            leaderboardType: 'faculty',
            sortType: 'point',

            init() {
                this.fetchStats();
                this.fetchRewards();
                this.fetchAllLeaderboards();
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

            scrollTo(id) {
                const el = document.getElementById(id);
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth' });
                    this.isMenuOpen = false;
                }
            },

            getFirstThaiChar(name) {
                if (!name || name.length === 0) return '?';
                const leadingVowels = ['เ', 'แ', 'โ', 'ใ', 'ไ'];
                if (leadingVowels.includes(name.charAt(0)) && name.length > 1) {
                    return name.charAt(1);
                }
                return name.charAt(0);
            },

            getSortValue(item) {
                if (!item) return '';
                switch (this.sortType) {
                    case 'goodnesss':
                        return item.goodness;
                    case 'social':
                        return item.social;
                    case 'point':
                    default:
                        return item.points;
                }
            },

            getSortLabel() {
                switch (this.sortType) {
                    case 'goodness':
                        return 'แต้มความดี';
                    case 'social':
                        return 'แต้มสังคม';
                    case 'point':
                    default:
                        return 'แต้มขยะ';
                }
            },
            get activeLeaderboard() {
                if (this.leaderboardType === 'faculty') return this.facultyLeaderboard;
                if (this.leaderboardType === 'major') return this.majorLeaderboard;
                return this.memberLeaderboard;
            },

            setLeaderboardType(type) {
                this.leaderboardType = type;
                this.$nextTick(() => {
                    if (window.lucide) {
                        lucide.createIcons();
                    }
                });
            },

            setSortType(type) {
                this.sortType = type;
                this.fetchAllLeaderboards();
                this.$nextTick(() => { if (window.lucide) lucide.createIcons(); });
            },

            async fetchAllLeaderboards() {
                try {
                    const response = await fetch(`/api/leaders/all?limit=10&page=1&sort=${this.sortType}`);
                    const result = await response.json();
                    if (result.success && result.data) {
                        // Faculty
                        this.facultyLeaderboard = result.data.faculty.map((item, index) => ({
                            rank: index + 1,
                            name: `คณะ ${item.faculty_name}`,
                            points: `${parseInt(item.total_point).toLocaleString()} แต้ม`,
                            goodness: `${parseInt(item.total_goodness).toLocaleString()} แต้ม`,
                            social: `${parseInt(item.total_social).toLocaleString()} แต้ม`,
                        }));

                        // Major
                        this.majorLeaderboard = result.data.major.map((item, index) => ({
                            rank: index + 1,
                            name: item.major_name,
                            faculty_name: `คณะ ${item.faculty_name}`,
                            points: `${parseInt(item.total_point).toLocaleString()} แต้ม`,
                            goodness: `${parseInt(item.total_goodness).toLocaleString()} แต้ม`,
                            social: `${parseInt(item.total_social).toLocaleString()} แต้ม`,
                        }));

                        // Member
                        this.memberLeaderboard = result.data.member.map((item, index) => ({
                            rank: index + 1,
                            name: item.member_name || item.name || 'ไม่ระบุชื่อ',
                            points: `${parseInt(item.total_point).toLocaleString()} แต้ม`,
                            goodness: `${parseInt(item.total_goodness).toLocaleString()} แต้ม`,
                            social: `${parseInt(item.total_social).toLocaleString()} แต้ม`,
                            fname: item.faculty_name || 'ไม่ระบุคณะ',
                            mname: item.major_name || 'ไม่ระบุสาขา'
                        }));

                        setTimeout(() => { if (window.lucide) lucide.createIcons(); }, 100);
                    }
                } catch (error) {
                    console.error('Error fetching leaderboard:', error);
                }
            },

        }))
    })
</script>