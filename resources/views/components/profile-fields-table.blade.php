<tr>
    <th colspan="2" class="text-lg">Profil Pengguna</th>
</tr>

<tr>
    <th>Nomor Identitas (NIS/NIP)</th>
    <td>
        <x-input-text name="identifier_number" type="text" model="form.userProfile.identifier_number"
            placeholder="Masukkan ID..." custom="idcard" />
    </td>
</tr>

<tr>
    <th>Jabatan</th>
    <td>
        <x-input-text name="position" type="text" model="form.userProfile.position" placeholder="Masukkan jabatan..."
            custom="person" />
    </td>
</tr>

<tr>
    <th>Alamat</th>
    <td>
        <x-input-text name="address" type="text" model="form.userProfile.address" placeholder="Masukkan alamat..."
            custom="address" />
    </td>
</tr>

<tr>
    <th>Telepon (HP/WA)</th>
    <td>
        <x-input-text name="phone" type="tel" model="form.userProfile.phone" placeholder="Contoh: 08xxxxxxxxxx"
            custom="phone" />
    </td>
</tr>

<tr>
    <th>Jenis Kelamin</th>
    <td>
        <x-input-select name="gender" :options="[
            ['value' => 'male', 'text' => 'Laki-laki'],
            ['value' => 'female', 'text' => 'Perempuan'],
            ['value' => 'other', 'text' => 'Lainnya'],
        ]" model="form.userProfile.gender"
            placeholder="Pilih jenis kelamin..." />
    </td>
</tr>
