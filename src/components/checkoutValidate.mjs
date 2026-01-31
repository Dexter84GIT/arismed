export const validatePhone = (raw) => {
  if (!raw) return null;

  let digits = raw.replace(/\D/g, '');

  if (!digits) return null;

  if (digits.startsWith('8')) {
    digits = '7' + digits.slice(1);
  } else if (digits.startsWith('9')) {
    digits = '7' + digits;
  } else if (!digits.startsWith('7')) {
    return null;
  }

  if (digits.length !== 11) return null;

  if (digits[1] === '0') return null;

  return `+${digits}`;
};
