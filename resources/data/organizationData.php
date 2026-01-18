<?php

return [
    [
        'id' => 1,
        'name' => 'BEM UNKLAB',
        'tagline' => 'Bersama Membangun, Bersama Melayani',
        'banner' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d',
        'logo' => '🏛️',
        'visi' => 'Mewujudkan mahasiswa yang berintegritas dan berkontribusi bagi bangsa.',
        'misi' => [
            'Meningkatkan kualitas pendidikan mahasiswa.',
            'Mendorong partisipasi aktif dalam kegiatan kemahasiswaan.',
            'Mengembangkan kepemimpinan dan karakter.'
        ],
        'culture' => 'Kolaborasi, Integritas, Pelayanan',
        'activeMembers' => 120,
        'programs' => [
            [
                'id' => 1,
                'title' => 'Program Pengabdian Masyarakat',
                'goal' => 'Memberdayakan masyarakat sekitar melalui pelatihan.',
                'activities' => ['Pelatihan keterampilan','Penyuluhan kesehatan'],
                'period' => '2025',
                'impact' => 'Meningkatkan kesejahteraan warga lokal'
            ],
        ],
        'events' => [
            [
                'id' => 1,
                'title' => 'Seminar Kepemimpinan',
                'date' => '2025-05-12',
                'images' => ['https://images.unsplash.com/photo-1655472355485-d949925e67bb'],
                'highlights' => 'Sesi pengembangan kepemimpinan',
                'description' => 'Seminar untuk membentuk karakter kepemimpinan mahasiswa.'
            ]
        ],
        'structure' => [
            ['position' => 'Ketua', 'name' => 'Andi'],
            ['position' => 'Wakil', 'name' => 'Budi'],
        ],
        'socialMedia' => [
            'instagram' => 'https://instagram.com/bemunklab',
            'whatsapp' => 'https://wa.me/628123456789',
            'email' => 'bem@unklab.ac.id',
            'website' => 'https://unklab.ac.id'
        ]
    ],
    [
        'id' => 2,
        'name' => 'UNKLAB Choir',
        'tagline' => 'Menyanyikan Kemuliaan Tuhan',
        'banner' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4',
        'logo' => '🎵',
        'visi' => 'Menjadi paduan suara teladan yang menginspirasi.',
        'misi' => ['Mengembangkan bakat vokal','Mengadakan konser kemanusiaan'],
        'culture' => 'Disiplin, Seni, Spiritual',
        'activeMembers' => 40,
        'programs' => [],
        'events' => [],
        'structure' => [
            ['position' => 'Dirigen', 'name' => 'Clara']
        ],
        'socialMedia' => ['instagram'=>'https://instagram.com/unklabchoir','email'=>'choir@unklab.ac.id']
    ],
    [
        'id' => 3,
        'name' => 'Creative Media Club',
        'tagline' => 'Kreativitas Tanpa Batas',
        'banner' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085',
        'logo' => '🎨',
        'visi' => 'Menjadi ruang kreasi bagi mahasiswa.',
        'misi' => ['Menghasilkan karya kreatif','Menyelenggarakan workshop'],
        'culture' => 'Kreatif, Inovatif',
        'activeMembers' => 60,
        'programs' => [],
        'events' => [],
        'structure' => [],
        'socialMedia' => []
    ],
    [
        'id' => 4,
        'name' => 'PMKO',
        'tagline' => 'Persekutuan Mahasiswa Kristen Oikumene',
        'banner' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1',
        'logo' => '⛪',
        'visi' => 'Menjadi wadah iman dan pelayanan.',
        'misi' => ['Pembinaan rohani','Pelayanan masyarakat'],
        'culture' => 'Iman, Pelayanan',
        'activeMembers' => 80,
        'programs' => [],
        'events' => [],
        'structure' => [],
        'socialMedia' => []
    ],
    [
        'id' => 5,
        'name' => 'Ikatan Mahasiswa Manado',
        'tagline' => 'Persatuan Anak Manado',
        'banner' => 'https://images.unsplash.com/photo-1503264116251-35a269479413',
        'logo' => '🏝️',
        'visi' => 'Mempererat ikatan mahasiswa asal Manado.',
        'misi' => ['Menjaga budaya','Menyelenggarakan kegiatan regional'],
        'culture' => 'Solidaritas, Budaya',
        'activeMembers' => 50,
        'programs' => [],
        'events' => [],
        'structure' => [],
        'socialMedia' => []
    ],
    // Additional static organizations
    [ 'id'=>6, 'name'=>'Photography Club', 'tagline'=>'Mengabadikan Momen', 'banner'=>'https://images.unsplash.com/photo-1518791841217-8f162f1e1131', 'logo'=>'📷', 'visi'=>'','misi'=>[], 'culture'=>'','activeMembers'=>30,'programs'=>[],'events'=>[],'structure'=>[],'socialMedia'=>[] ],
    [ 'id'=>7, 'name'=>'Himpunan Mahasiswa Teknik', 'tagline'=>'Berinovasi untuk Negeri', 'banner'=>'https://images.unsplash.com/photo-1518779578993-ec3579fee39f', 'logo'=>'🔧', 'visi'=>'','misi'=>[], 'culture'=>'','activeMembers'=>200,'programs'=>[],'events'=>[],'structure'=>[],'socialMedia'=>[] ],
    [ 'id'=>8, 'name'=>'English Club', 'tagline'=>'Bahasa untuk Dunia', 'banner'=>'https://images.unsplash.com/photo-1503676260728-1c00da094a0b', 'logo'=>'🗣️', 'visi'=>'','misi'=>[], 'culture'=>'','activeMembers'=>25,'programs'=>[],'events'=>[],'structure'=>[],'socialMedia'=>[] ],
];
