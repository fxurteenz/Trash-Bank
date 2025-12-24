async function OnSubmit(e) {
    if (e) e.preventDefault(); // ถ้าเรียกจาก <form onSubmit={...}>

    console.log("submit");

    const identifierInput = document.getElementById("identifier");
    const passwordInput = document.getElementById("password");

    if (!identifierInput || !passwordInput) {
        console.error("ไม่พบช่องกรอกข้อมูลหรือรหัสผ่าน");
        return;
    }

    const identifier = identifierInput.value.trim();
    const password = passwordInput.value;

    if (!identifier || !password) {
        console.error("กรุณากรอกข้อมูลและรหัสผ่านให้ครบถ้วน");
        return;
    }

    try {
        const response = await fetch("/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ identifier, password }),
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || `HTTP ${response.status}`);
        }

        const result = await response.json();
        console.log("Login success:", result);
        if (result.success) {
            await Swal.fire({
                icon: "success",
                title: "เข้าสู่ระบบสำเร็จ",
                text: "กำลังพาท่านเข้าสู่ระบบ...",
                timer: 1500,
                showConfirmButton: false,
            });

            if (result.result.user.account_role === "admin") {
                window.location.href = "/admin";
            } else if (result.result.user.account_role === "operater") {
                window.location.href = "/operater";
            }else if (result.result.user.account_role === "faculty_staff") {
                window.location.href = "/faculty_staff";
            } else if (result.result.user.account_role === "user") {
                window.location.href = "/user";   
            }else {
                // console.log(result);
                throw new Error("Hacker ? ", 500);
            }
        } else {
            throw new Error(result.message || result, 500);
        }
    } catch (error) {
        console.error("Login failed:", error);
        Swal.fire({
            icon: "error",
            title: "เข้าสู่ระบบไม่สำเร็จ",
            text: error.message || "ข้อมูลหรือรหัสผ่านไม่ถูกต้อง",
        });
    }
}
