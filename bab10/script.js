if (confirm("Apakah Anda mahasiswa PPW1?")) {
    const name = prompt("masukkan nama");
    const nim = prompt("masukkan niu");
    const angkatan = prompt("masukkan angkatan")

    document.getElementById("nama").innerHTML = name
    document.getElementById("nim").innerHTML = nim
    document.getElementById("angkatan").innerHTML = angkatan
    document.getElementById("lulus").innerHTML = Number(angkatan) + 4
    document.getElementById("gatau").innerHTML = Number(nim) % 2 ? "Ganjil" : "Genap"

    document.write("<h2>saya dari document.write!</h2>")

    const target = document.getElementById("osas");
target.innerHTML = "saya dari inner HTML!"
} else {
    const target = document.getElementById("root")
    target.innerHTML = "Hidup JOKOWIIIIIIIIIIIIIIIIIIIIIIii"
}

