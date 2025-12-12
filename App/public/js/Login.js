async function OnSubmit(e) {
    if (e) e.preventDefault(); // ถ้าเรียกจาก <form onSubmit={...}>

    console.log("submit");

    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");

    if (!emailInput || !passwordInput) {
        console.error("ไม่พบช่องกรอกอีเมลหรือรหัสผ่าน");
        return;
    }

    const email = emailInput.value.trim();
    const password = passwordInput.value;

    if (!email || !password) {
        console.error("กรุณากรอกอีเมลและรหัสผ่านให้ครบถ้วน");
        return;
    }

    try {
        const response = await fetch("/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ email, password }),
        });

        if (!response.ok) {
            const error = await response.json();
            throw new Error(error.message || `HTTP ${response.status}`);
        }

        const result = await response.json();
        console.log("Login success:", result);
        if (result.success) {
            if (result.result.user.account_role === "admin") {
                window.location.href = "/admin";
            } else if (result.result.user.account_role === "operater") {
                window.location.href = "/operater";
            } else {
                // console.log(result);
                throw new Error("Hacker ? ", 500);
            }
        } else {
            throw new Error(result.message || result, 500);
        }
        // ตัวอย่าง: เก็บ token แล้ว redirect
        // if (result.token) {
        //     localStorage.setItem("token", result.token);
        // }
        // ไปหน้าหลักหรือ dashboard
        // window.location.href = "/admin";
    } catch (error) {
        console.error("Login failed:", error);
        alert(error.message || "อีเมลหรือรหัสผ่านไม่ถูกต้อง");
    }
}
