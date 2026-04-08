<?php

namespace Tests\Feature;

use Tests\TestCase;

class RouteAndApiSyncTest extends TestCase
{
    public function test_critical_named_routes_are_registered_and_reachable(): void
    {
        $routes = [
            'home' => 200,
            'login' => 200,
            'portal.login' => 200,
            'portal.admin.dashboard' => 200,
            'portal.kemahasiswaan.dashboard' => 200,
            'portal.kemahasiswaan.organisasi' => 200,
            'portal.kemahasiswaan.pengajuan' => 200,
            'portal.kemahasiswaan.pengumuman' => 200,
            'portal.kemahasiswaan.notifikasi' => 200,
            'portal.pengurus.dashboard' => 200,
            'portal.pengurus.events' => 200,
            'portal.pengurus.events.create' => 200,
            'portal.pengurus.announcements' => 200,
            'portal.pengurus.announcements.create' => 200,
            'portal.pengurus.lostandfound' => 200,
            'portal.pengurus.proposals' => 200,
            'portal.pengurus.members' => 200,
            'portal.pengurus.applications' => 200,
            'portal.pengurus.settings' => 200,
            'portal.pengurus.reports' => 200,
            'portal.pengurus.submissions' => 200,
            'mahasiswa.beranda' => 200,
            'mahasiswa.organisasi.index' => 200,
            'mahasiswa.event' => 200,
            'mahasiswa.pengumuman' => 200,
            'mahasiswa.tentang' => 200,
            'mahasiswa.lost-found' => 200,
            'events.index' => 302,
            'announcements.index' => 302,
            'proposals.index' => 302,
            'messages.index' => 200,
            'profil.show' => 200,
            'lostfound.index' => 302,
        ];

        foreach ($routes as $name => $expectedStatus) {
            $url = route($name);
            $this->assertNotSame('', $url, "Route {$name} is not registered.");

            $response = $this->get($url);
            $response->assertStatus($expectedStatus);
        }
    }

    public function test_api_endpoints_return_json_contracts(): void
    {
        $this->getJson(route('api.pengumuman.index'))
            ->assertOk()
            ->assertJsonStructure([
                '*' => ['id', 'judul', 'kategori', 'author', 'date'],
            ]);

        $this->getJson(route('api.pengumuman.detail', ['id' => 1]))
            ->assertOk()
            ->assertJsonStructure(['id', 'judul', 'konten', 'kategori', 'author', 'date']);

        $this->getJson(route('api.organisasi.index'))
            ->assertOk()
            ->assertJsonStructure([
                '*' => ['id', 'name', 'tagline', 'activeMembers'],
            ]);

        $this->getJson(route('api.organisasi.show', ['id' => 1]))
            ->assertOk()
            ->assertJsonStructure(['id', 'name']);

        $this->getJson(route('api.lost-found.index'))
            ->assertOk()
            ->assertJsonStructure([
                '*' => ['id', 'title', 'type', 'status', 'location'],
            ]);

        $this->getJson(route('api.lost-found.detail', ['id' => 1]))
            ->assertOk()
            ->assertJsonStructure(['id', 'title', 'description', 'type', 'status', 'location']);
    }

    public function test_api_detail_endpoints_return_404_for_missing_items(): void
    {
        $this->getJson(route('api.pengumuman.detail', ['id' => 999]))->assertNotFound();
        $this->getJson(route('api.organisasi.show', ['id' => 999]))->assertNotFound();
        $this->getJson(route('api.lost-found.detail', ['id' => 999]))->assertNotFound();
    }
}
