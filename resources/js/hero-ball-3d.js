/**
 * 3D Animated Interactive Soccer Ball for Bee Bold Soccer School Hero Section
 * Implements pure Canvas 3D truncated icosahedron (classic 32-panel soccer ball)
 * with realistic lighting, orbital energy rings, 3D particles, physics bobbing,
 * ground contact shadow, inertia spin, and touch/click interactions.
 * Zero external dependencies.
 */

export function initHeroBall3D() {
    const canvas = document.getElementById('hero-3d-ball-canvas');
    if (!canvas) return;

    const container = canvas.parentElement;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    // --- 1. SOCCER BALL GEOMETRY (Truncated Icosahedron: 12 pentagons + 20 hexagons) ---
    const phi = (1 + Math.sqrt(5)) / 2;
    let icoVertices = [
        [-1, phi, 0], [1, phi, 0], [-1, -phi, 0], [1, -phi, 0],
        [0, -1, phi], [0, 1, phi], [0, -1, -phi], [0, 1, -phi],
        [phi, 0, -1], [phi, 0, 1], [-phi, 0, -1], [-phi, 0, 1]
    ];

    const norm = (v) => {
        const l = Math.hypot(v[0], v[1], v[2]) || 1;
        return [v[0] / l, v[1] / l, v[2] / l];
    };

    icoVertices = icoVertices.map(norm);

    const icoFaces = [
        [0, 11, 5], [0, 5, 1], [0, 1, 7], [0, 7, 10], [0, 10, 11],
        [1, 5, 9], [5, 11, 4], [11, 10, 2], [10, 7, 6], [7, 1, 8],
        [3, 9, 4], [3, 4, 2], [3, 2, 6], [3, 6, 8], [3, 8, 9],
        [4, 9, 5], [2, 4, 11], [6, 2, 10], [8, 6, 7], [9, 8, 1]
    ];

    const lerp = (a, b, t) => [
        a[0] + (b[0] - a[0]) * t,
        a[1] + (b[1] - a[1]) * t,
        a[2] + (b[2] - a[2]) * t
    ];

    // Hexagons from cutting the 20 triangular faces
    const hexagons = [];
    for (const [aIdx, bIdx, cIdx] of icoFaces) {
        const A = icoVertices[aIdx], B = icoVertices[bIdx], C = icoVertices[cIdx];
        const hex = [
            norm(lerp(A, B, 1 / 3)),
            norm(lerp(A, B, 2 / 3)),
            norm(lerp(B, C, 1 / 3)),
            norm(lerp(B, C, 2 / 3)),
            norm(lerp(C, A, 1 / 3)),
            norm(lerp(C, A, 2 / 3))
        ];
        const center = norm(hex.reduce((acc, v) => [acc[0] + v[0], acc[1] + v[1], acc[2] + v[2]], [0, 0, 0]));
        hexagons.push({ type: 'hex', vertices: hex, center });
    }

    // Pentagons from cutting around the 12 vertices
    const pentagons = [];
    for (let i = 0; i < icoVertices.length; i++) {
        const V = icoVertices[i];
        const adjFaces = icoFaces.filter(f => f.includes(i));
        const pts = [];
        for (const f of adjFaces) {
            const others = f.filter(idx => idx !== i);
            for (const oIdx of others) {
                const p = norm(lerp(V, icoVertices[oIdx], 1 / 3));
                if (!pts.some(e => Math.hypot(e[0] - p[0], e[1] - p[1], e[2] - p[2]) < 0.001)) {
                    pts.push(p);
                }
            }
        }
        const ref = [pts[0][0] - V[0], pts[0][1] - V[1], pts[0][2] - V[2]];
        const cross = [
            V[1] * ref[2] - V[2] * ref[1],
            V[2] * ref[0] - V[0] * ref[2],
            V[0] * ref[1] - V[1] * ref[0]
        ];
        pts.sort((p1, p2) => {
            const v1 = [p1[0] - V[0], p1[1] - V[1], p1[2] - V[2]];
            const v2 = [p2[0] - V[0], p2[1] - V[1], p2[2] - V[2]];
            const dot1 = v1[0] * ref[0] + v1[1] * ref[1] + v1[2] * ref[2];
            const det1 = v1[0] * cross[0] + v1[1] * cross[1] + v1[2] * cross[2];
            const ang1 = Math.atan2(det1, dot1);
            const dot2 = v2[0] * ref[0] + v2[1] * ref[1] + v2[2] * ref[2];
            const det2 = v2[0] * cross[0] + v2[1] * cross[1] + v2[2] * cross[2];
            const ang2 = Math.atan2(det2, dot2);
            return ang1 - ang2;
        });
        const center = norm(pts.reduce((acc, v) => [acc[0] + v[0], acc[1] + v[1], acc[2] + v[2]], [0, 0, 0]));
        pentagons.push({ type: 'pent', vertices: pts, center });
    }

    const allPanels = [...hexagons, ...pentagons];

    // --- 2. 3D ORBITAL RINGS & PARTICLES ---
    const ringCount = 2;
    const ringRadiusMult = [1.32, 1.48];
    const ringRotations = [
        { rx: 1.1, rz: 0.4, speed: 0.012, color: '#ffcc00' }, // Gold Ring
        { rx: -0.85, rz: -0.5, speed: -0.015, color: '#66ccff' } // Sky Cyan Ring
    ];

    const particleCount = 28;
    const particles = [];
    for (let i = 0; i < particleCount; i++) {
        const theta = Math.random() * Math.PI * 2;
        const phiAngle = (Math.random() - 0.5) * Math.PI;
        const dist = 1.25 + Math.random() * 0.55;
        particles.push({
            x: Math.cos(theta) * Math.cos(phiAngle) * dist,
            y: Math.sin(phiAngle) * dist,
            z: Math.sin(theta) * Math.cos(phiAngle) * dist,
            radius: 1.5 + Math.random() * 2.2,
            speed: (0.008 + Math.random() * 0.014) * (Math.random() > 0.5 ? 1 : -1),
            color: Math.random() > 0.4 ? '#ffcc00' : '#66ccff',
            opacity: 0.35 + Math.random() * 0.55,
            orbitAngle: theta
        });
    }

    // Spark bursts on click/tap
    const clickSparks = [];

    // --- 3. ROTATION & PHYSICS STATE ---
    let rotX = 0.25;
    let rotY = -0.4;
    let rotZ = 0.05;
    let velX = 0.004;
    let velY = 0.008;

    let targetParallaxX = 0;
    let targetParallaxY = 0;
    let currentParallaxX = 0;
    let currentParallaxY = 0;

    let isDragging = false;
    let lastPointerX = 0;
    let lastPointerY = 0;
    let lastPointerTime = 0;
    let dragDist = 0;

    // Bobbing / Floating physics
    let bobTime = 0;
    let kickImpulse = 0;

    // Screen dimension tracking with safe mobile fallback
    let width = container.clientWidth || 320;
    let height = container.clientHeight || 280;
    let dpr = Math.min(window.devicePixelRatio || 1, 2);

    const resize = () => {
        const cw = container.clientWidth || 320;
        const ch = container.clientHeight || 280;
        if (cw > 0 && ch > 0) {
            width = cw;
            height = ch;
        }
        canvas.width = width * dpr;
        canvas.height = height * dpr;
        canvas.style.width = `${width}px`;
        canvas.style.height = `${height}px`;
    };
    resize();

    window.addEventListener('resize', resize, { passive: true });
    // Safe retry for mobile layout reflow
    setTimeout(resize, 100);
    setTimeout(resize, 500);

    // --- 4. LIGHTING CONFIG ---
    // Directional light from top-left-front
    const lightDir = norm([-0.45, -0.65, 0.75]);

    // Matrix / Vector transformations
    const rotateX = (v, angle) => {
        const cos = Math.cos(angle), sin = Math.sin(angle);
        return [v[0], v[1] * cos - v[2] * sin, v[1] * sin + v[2] * cos];
    };

    const rotateY = (v, angle) => {
        const cos = Math.cos(angle), sin = Math.sin(angle);
        return [v[0] * cos + v[2] * sin, v[1], -v[0] * sin + v[2] * cos];
    };

    const rotateZ = (v, angle) => {
        const cos = Math.cos(angle), sin = Math.sin(angle);
        return [v[0] * cos - v[1] * sin, v[0] * sin + v[1] * cos, v[2]];
    };

    const transformPoint = (p) => {
        let res = rotateY(p, rotY);
        res = rotateX(res, rotX);
        res = rotateZ(res, rotZ);
        return res;
    };

    // --- 5. RENDER LOOP ---
    let isVisible = true;
    let animFrameId = null;

    const observer = new IntersectionObserver(([entry]) => {
        isVisible = entry ? entry.isIntersecting : true;
        if (isVisible && !animFrameId) {
            animFrameId = requestAnimationFrame(render);
        }
    }, { rootMargin: '250px 0px', threshold: 0 });
    observer.observe(canvas);

    const triggerKick = (clientX, clientY) => {
        kickImpulse = 1.0;
        const rect = canvas.getBoundingClientRect();
        const originX = (clientX - rect.left) * dpr;
        const originY = (clientY - rect.top) * dpr;

        // Add 16 dynamic sparks
        for (let i = 0; i < 18; i++) {
            const angle = Math.random() * Math.PI * 2;
            const speed = 2.5 + Math.random() * 5.5;
            clickSparks.push({
                x: originX,
                y: originY,
                vx: Math.cos(angle) * speed,
                vy: Math.sin(angle) * speed,
                life: 1.0,
                color: Math.random() > 0.4 ? '#ffcc00' : '#66ccff',
                size: 2.5 + Math.random() * 3
            });
        }
    };

    function render() {
        if (!isVisible) {
            animFrameId = null;
            return;
        }

        ctx.save();
        ctx.scale(dpr, dpr);
        ctx.clearRect(0, 0, width, height);

        // --- Physics & Inertia Updates ---
        bobTime += 0.024;
        kickImpulse *= 0.92;
        if (kickImpulse < 0.001) kickImpulse = 0;

        if (!isDragging) {
            rotY += velY;
            rotX += velX;
            // Damping velocity toward continuous gentle auto-spin
            velY = velY * 0.965 + 0.005 * 0.035;
            velX = velX * 0.965 + 0.002 * 0.035;
        }

        // Smooth Parallax tilt
        currentParallaxX += (targetParallaxX - currentParallaxX) * 0.06;
        currentParallaxY += (targetParallaxY - currentParallaxY) * 0.06;

        const effectiveRotX = rotX + currentParallaxY * 0.35;
        const effectiveRotY = rotY + currentParallaxX * 0.45;

        // Ball size calculation based on container width
        const baseRadius = Math.min(width, height) * 0.32;
        const ballRadius = baseRadius * (1 + kickImpulse * 0.08);

        // Center position with floating bobbing & impulse jolt
        const cx = width / 2;
        const cy = height / 2 + Math.sin(bobTime) * 14 - kickImpulse * 18;

        // --- A. CONTACT GROUND SHADOW ---
        const shadowY = height / 2 + baseRadius * 1.28;
        const shadowScale = 1 - (cy - height / 2) / 100;
        const shadowRadiusX = baseRadius * 0.85 * shadowScale;
        const shadowRadiusY = baseRadius * 0.22 * shadowScale;
        const shadowAlpha = Math.max(0.2, 0.48 * shadowScale);

        const shadowGrad = ctx.createRadialGradient(cx, shadowY, 0, cx, shadowY, shadowRadiusX);
        shadowGrad.addColorStop(0, `rgba(0, 8, 20, ${shadowAlpha})`);
        shadowGrad.addColorStop(0.5, `rgba(0, 18, 40, ${shadowAlpha * 0.5})`);
        shadowGrad.addColorStop(1, 'rgba(0, 18, 40, 0)');

        ctx.beginPath();
        ctx.ellipse(cx, shadowY, shadowRadiusX, shadowRadiusY, 0, 0, Math.PI * 2);
        ctx.fillStyle = shadowGrad;
        ctx.fill();

        // --- B. ORBITAL RINGS (BACK HALF: z < 0) ---
        const drawRingSegment = (isFront) => {
            ringRotations.forEach((ring, idx) => {
                const r = ballRadius * ringRadiusMult[idx];
                const angleOffset = bobTime * ring.speed * 20;
                ctx.beginPath();
                let started = false;

                const steps = 72;
                for (let i = 0; i <= steps; i++) {
                    const theta = (i / steps) * Math.PI * 2;
                    let pt = [Math.cos(theta) * r, Math.sin(theta) * r, 0];
                    pt = rotateX(pt, ring.rx);
                    pt = rotateZ(pt, ring.rz + angleOffset);

                    const inFront = pt[2] >= 0;
                    if (inFront === isFront) {
                        const sx = cx + pt[0];
                        const sy = cy + pt[1];
                        if (!started) {
                            ctx.moveTo(sx, sy);
                            started = true;
                        } else {
                            ctx.lineTo(sx, sy);
                        }
                    } else {
                        started = false;
                    }
                }
                ctx.lineWidth = isFront ? 2.5 : 1.5;
                ctx.strokeStyle = ring.color;
                ctx.globalAlpha = isFront ? 0.75 : 0.25;
                ctx.shadowColor = ring.color;
                ctx.shadowBlur = isFront ? 14 : 4;
                ctx.stroke();
                ctx.shadowBlur = 0;
                ctx.globalAlpha = 1.0;
            });
        };

        drawRingSegment(false);

        // --- C. 3D FLOATING PARTICLES (BACK HALF: z < 0) ---
        const drawParticles = (isFront) => {
            particles.forEach((p) => {
                p.orbitAngle += p.speed;
                const r = ballRadius * (1.2 + Math.sin(bobTime + p.orbitAngle) * 0.15);
                let pt = [
                    Math.cos(p.orbitAngle) * r,
                    Math.sin(p.orbitAngle * 0.7 + bobTime) * (r * 0.65),
                    Math.sin(p.orbitAngle) * r
                ];
                pt = rotateX(pt, 0.4);
                pt = rotateY(pt, effectiveRotY * 0.5);

                const inFront = pt[2] >= 0;
                if (inFront === isFront) {
                    const sx = cx + pt[0];
                    const sy = cy + pt[1];
                    const depthAlpha = isFront ? p.opacity : p.opacity * 0.3;

                    ctx.beginPath();
                    ctx.arc(sx, sy, p.radius, 0, Math.PI * 2);
                    ctx.fillStyle = p.color;
                    ctx.globalAlpha = depthAlpha;
                    ctx.shadowColor = p.color;
                    ctx.shadowBlur = isFront ? 8 : 2;
                    ctx.fill();
                    ctx.shadowBlur = 0;
                    ctx.globalAlpha = 1.0;
                }
            });
        };

        drawParticles(false);

        // --- D. AMBIENT GLOW BEHIND BALL ---
        const glowGrad = ctx.createRadialGradient(cx, cy, ballRadius * 0.4, cx, cy, ballRadius * 1.5);
        glowGrad.addColorStop(0, 'rgba(255, 204, 0, 0.16)');
        glowGrad.addColorStop(0.5, 'rgba(102, 204, 255, 0.08)');
        glowGrad.addColorStop(1, 'rgba(0, 18, 40, 0)');
        ctx.beginPath();
        ctx.arc(cx, cy, ballRadius * 1.5, 0, Math.PI * 2);
        ctx.fillStyle = glowGrad;
        ctx.fill();

        // --- E. SOCCER BALL SPHERE RENDERING ---
        // Transform and depth-sort all 32 panels
        const transformedPanels = allPanels.map((panel) => {
            const transformedCenter = rotateZ(rotateX(rotateY(panel.center, effectiveRotY), effectiveRotX), rotZ);
            const transformedVerts = panel.vertices.map((v) =>
                rotateZ(rotateX(rotateY(v, effectiveRotY), effectiveRotX), rotZ)
            );
            return {
                type: panel.type,
                center: transformedCenter,
                vertices: transformedVerts
            };
        });

        // Depth sort from back (smallest Z) to front (largest Z)
        transformedPanels.sort((a, b) => a.center[2] - b.center[2]);

        // Perspective projection factor
        const fov = 400;

        // Render each visible panel
        for (const panel of transformedPanels) {
            // Backface culling: if normal points away from camera, skip
            if (panel.center[2] < -0.15) continue;

            const normal = panel.center;
            // Diffuse lighting calculation (dot product with lightDir)
            const diffuse = Math.max(0, normal[0] * lightDir[0] + normal[1] * lightDir[1] + normal[2] * lightDir[2]);

            // Specular Blinn-Phong highlight
            const halfX = lightDir[0];
            const halfY = lightDir[1];
            const halfZ = lightDir[2] + 1;
            const halfLen = Math.hypot(halfX, halfY, halfZ) || 1;
            const spec = Math.pow(Math.max(0, (normal[0] * halfX + normal[1] * halfY + normal[2] * halfZ) / halfLen), 20);

            // Rim Fresnel effect
            const rim = Math.pow(1 - Math.max(0, normal[2]), 2.2);

            // Project polygon vertices to 2D
            const screenVerts = panel.vertices.map((v) => {
                const proj = fov / (fov + v[2] * 0.4);
                return [
                    cx + v[0] * ballRadius * proj,
                    cy + v[1] * ballRadius * proj
                ];
            });

            // Draw panel polygon
            ctx.beginPath();
            ctx.moveTo(screenVerts[0][0], screenVerts[0][1]);
            for (let i = 1; i < screenVerts.length; i++) {
                ctx.lineTo(screenVerts[i][0], screenVerts[i][1]);
            }
            ctx.closePath();

            // Color grading based on panel type
            if (panel.type === 'pent') {
                // Bee Bold Navy Pentagons with gold rim & rich shading
                const baseDark = 12 + diffuse * 32;
                const baseNavyBlue = 30 + diffuse * 65;
                const r = Math.min(255, Math.floor(baseDark + rim * 30 + spec * 120));
                const g = Math.min(255, Math.floor(baseDark * 1.2 + rim * 45 + spec * 140));
                const b = Math.min(255, Math.floor(baseNavyBlue + rim * 70 + spec * 200));

                ctx.fillStyle = `rgb(${r}, ${g}, ${b})`;
                ctx.fill();

                // Golden inner stitch border for pentagons
                ctx.lineWidth = 2.2;
                ctx.strokeStyle = `rgba(255, 204, 0, ${0.45 + diffuse * 0.45})`;
                ctx.stroke();

                // Subtle gold center accent star/dot if facing front
                if (normal[2] > 0.5) {
                    const centerProj = fov / (fov + normal[2] * 0.4);
                    const pcx = cx + normal[0] * ballRadius * centerProj;
                    const pcy = cy + normal[1] * ballRadius * centerProj;
                    ctx.beginPath();
                    ctx.arc(pcx, pcy, 2.5 * centerProj, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(255, 204, 0, ${normal[2] * 0.8})`;
                    ctx.fill();
                }
            } else {
                // Pearl White Hexagons with leather sheen
                const baseLight = 140 + diffuse * 105;
                const r = Math.min(255, Math.floor(baseLight + spec * 160));
                const g = Math.min(255, Math.floor(baseLight + spec * 160));
                const b = Math.min(255, Math.floor((baseLight + 8) + rim * 25 + spec * 170));

                ctx.fillStyle = `rgb(${r}, ${g}, ${b})`;
                ctx.fill();

                // Deep seam stitches
                ctx.lineWidth = 2.0;
                ctx.strokeStyle = 'rgba(0, 24, 52, 0.55)';
                ctx.stroke();
            }
        }

        // --- F. SPHERE SPECULAR SHEEN OVERLAY ---
        const sheenGrad = ctx.createRadialGradient(
            cx - ballRadius * 0.35,
            cy - ballRadius * 0.35,
            ballRadius * 0.1,
            cx,
            cy,
            ballRadius
        );
        sheenGrad.addColorStop(0, 'rgba(255, 255, 255, 0.45)');
        sheenGrad.addColorStop(0.3, 'rgba(255, 255, 255, 0.08)');
        sheenGrad.addColorStop(0.75, 'rgba(0, 18, 40, 0.15)');
        sheenGrad.addColorStop(1, 'rgba(0, 10, 25, 0.65)');

        ctx.save();
        ctx.beginPath();
        ctx.arc(cx, cy, ballRadius - 0.5, 0, Math.PI * 2);
        ctx.fillStyle = sheenGrad;
        ctx.fill();
        ctx.restore();

        // --- G. RIM LIGHT GLOW ON EDGES ---
        ctx.save();
        ctx.beginPath();
        ctx.arc(cx, cy, ballRadius, 0, Math.PI * 2);
        ctx.lineWidth = 3.5;
        ctx.strokeStyle = 'rgba(255, 204, 0, 0.45)';
        ctx.shadowColor = '#ffcc00';
        ctx.shadowBlur = 12;
        ctx.stroke();
        ctx.restore();

        // --- H. ORBITAL RINGS & PARTICLES (FRONT HALF: z >= 0) ---
        drawRingSegment(true);
        drawParticles(true);

        // --- I. CLICK SPARKS BURST ---
        for (let i = clickSparks.length - 1; i >= 0; i--) {
            const spark = clickSparks[i];
            spark.x += spark.vx;
            spark.y += spark.vy;
            spark.vy += 0.12; // slight gravity
            spark.life -= 0.035;

            if (spark.life <= 0) {
                clickSparks.splice(i, 1);
                continue;
            }

            ctx.beginPath();
            ctx.arc(spark.x / dpr, spark.y / dpr, spark.size * spark.life, 0, Math.PI * 2);
            ctx.fillStyle = spark.color;
            ctx.globalAlpha = spark.life;
            ctx.shadowColor = spark.color;
            ctx.shadowBlur = 8;
            ctx.fill();
            ctx.shadowBlur = 0;
            ctx.globalAlpha = 1.0;
        }

        ctx.restore();
        animFrameId = requestAnimationFrame(render);
    }

    // --- 6. USER INTERACTIONS (MOUSE / TOUCH / HOVER) ---
    const onPointerDown = (e) => {
        isDragging = true;
        dragDist = 0;
        lastPointerX = e.clientX || (e.touches && e.touches[0].clientX) || 0;
        lastPointerY = e.clientY || (e.touches && e.touches[0].clientY) || 0;
        lastPointerTime = performance.now();
        canvas.style.cursor = 'grabbing';
    };

    const onPointerMove = (e) => {
        const clientX = e.clientX || (e.touches && e.touches[0].clientX) || 0;
        const clientY = e.clientY || (e.touches && e.touches[0].clientY) || 0;

        // Container-relative parallax
        const rect = canvas.getBoundingClientRect();
        targetParallaxX = ((clientX - rect.left) / rect.width - 0.5) * 2;
        targetParallaxY = ((clientY - rect.top) / rect.height - 0.5) * 2;

        if (!isDragging) return;

        const dx = clientX - lastPointerX;
        const dy = clientY - lastPointerY;
        const now = performance.now();
        const dt = Math.max(1, now - lastPointerTime);

        dragDist += Math.hypot(dx, dy);

        // Angular rotation mapping
        rotY += dx * 0.008;
        rotX += dy * 0.008;

        // Calculate velocity for inertia release
        velY = (dx / dt) * 0.18;
        velX = (dy / dt) * 0.18;

        lastPointerX = clientX;
        lastPointerY = clientY;
        lastPointerTime = now;
    };

    const onPointerUp = (e) => {
        if (!isDragging) return;
        isDragging = false;
        canvas.style.cursor = 'grab';

        // If it was a quick click/tap without dragging, trigger kick burst
        if (dragDist < 6) {
            const clientX = e.clientX || (e.changedTouches && e.changedTouches[0].clientX) || (width / 2);
            const clientY = e.clientY || (e.changedTouches && e.changedTouches[0].clientY) || (height / 2);
            triggerKick(clientX, clientY);
        }
    };

    canvas.style.cursor = 'grab';
    canvas.addEventListener('mousedown', onPointerDown);
    window.addEventListener('mousemove', onPointerMove, { passive: true });
    window.addEventListener('mouseup', onPointerUp);

    // Touch support (does not prevent page scroll unless active drag)
    canvas.addEventListener('touchstart', onPointerDown, { passive: true });
    canvas.addEventListener('touchmove', onPointerMove, { passive: true });
    canvas.addEventListener('touchend', onPointerUp, { passive: true });

    // Parallax on hero section hover
    const heroSection = document.getElementById('beranda');
    if (heroSection) {
        heroSection.addEventListener('mousemove', (e) => {
            if (isDragging) return;
            const rect = heroSection.getBoundingClientRect();
            targetParallaxX = ((e.clientX - rect.left) / rect.width - 0.5) * 1.5;
            targetParallaxY = ((e.clientY - rect.top) / rect.height - 0.5) * 1.5;
        }, { passive: true });

        heroSection.addEventListener('mouseleave', () => {
            targetParallaxX = 0;
            targetParallaxY = 0;
        }, { passive: true });
    }

    // Start loop
    animFrameId = requestAnimationFrame(render);
}
